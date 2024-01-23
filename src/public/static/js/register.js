(async () => {
    $('#registration-form input[type="text"], #registration-form input[type="email"]').on('keyup keypress', function (e) {
        const keyCode = e.keyCode || e.which;
        if (keyCode === 13) {
            e.preventDefault();
            return false;
        }

        return true;
    });

    /**
     * Function to generate RSA Public and Private Key pair.
     * @returns {{publicKeyPEM: string, privateKeyPEM: string}}
     */
    async function generateRSAKeyPair() {
        try {
            const algorithm = {
                name: "RSA-OAEP",
                modulusLength: 4096,
                publicExponent: new Uint8Array([0x01, 0x00, 0x01]),
                hash: "SHA-256"
            };

            const keyPair = await window.crypto.subtle.generateKey(
                algorithm,
                true, // True - Key can be extracted
                ["encrypt", "decrypt"] // Key usages
            );

            const spki = await window.crypto.subtle.exportKey("spki", keyPair.publicKey);
            const publicKeyPEM = arrayBufferToPEM(spki, 'PUBLIC KEY');
            const pkcs8 = await window.crypto.subtle.exportKey("pkcs8", keyPair.privateKey);
            const privateKeyPEM = arrayBufferToPEM(pkcs8, 'PRIVATE KEY');

            return {publicKeyPEM, privateKeyPEM, keyPair};
        } catch (error) {
            console.error("Key generation failed:", error);
        }
    }

    /**
     *
     * @param buffer
     * @param type
     * @returns {`-----BEGIN ${string}-----\n${string}\n-----END ${string}-----`}
     */
    const arrayBufferToPEM = (buffer, type) => {
        const base64 = arrayBufferToBase64(buffer);
        const formattedBase64 = base64.match(/.{1,64}/g).join('\n');
        return `-----BEGIN ${type}-----\n${formattedBase64}\n-----END ${type}-----`;
    };

    const saveKey = (dbName, storeName, key, keyType) => {
        let dbVersion = 0;
        getDBVersion(dbName)
            .then(version => {
                dbVersion = version;
                console.log(`Current version of the database '${dbName}': ${version}`);
                openDatabase(dbName, storeName, dbVersion).then(db => {
                    const transaction = db.transaction([storeName], 'readwrite', {durability: 'strict'});
                    const objectStore = transaction.objectStore(storeName);

                    const request = objectStore.put(key, keyType);
                    request.onsuccess = () => {
                        console.log('Key saved successfully');
                        db.close(); // Close the database connection when done
                    };

                    request.onerror = (event) => {
                        console.error('Error saving the key:', event.target.errorCode);
                    };
                }).catch(error => {
                    console.error('Database error:', error);
                });
            })
            .catch(error => {
                console.error('Error getting database version:', error);
            });
    };

    const btn = document.getElementById('register-btn');
    if (btn !== null) {
        btn.addEventListener('click', (e) => {
            const url = e.currentTarget.dataset.url;

            generateRSAKeyPair().then(keys => {
                const publicKeyPEM = keys.publicKeyPEM;
                const privateKeyPEM = keys.privateKeyPEM;

                let email = document.getElementById('register-email').value;
                saveKey(LocalDBName, email, publicKeyPEM, 'publicKeyPEM');
                saveKey(LocalDBName, email, privateKeyPEM, 'privateKeyPEM');
                saveKey(LocalDBName, email, keys.keyPair, 'kPair');

                $.ajax(url, {
                    method: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        name: document.getElementById('register-name').value,
                        email: email,
                        key: publicKeyPEM
                    })
                }).done((response) => {
                    if (!response.ok) {
                        console.error(response.reason);
                        return;
                    }

                    window.location.replace(response.to);
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error(textStatus, errorThrown);
                });
            });
        });
    }
})();