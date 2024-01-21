(async () => {

    /**
     * Function to generate RSA Public and Private Key pair.
     * @returns {{publicKeyPEM: string, privateKeyPEM: string}}
     */
    async function generateRSAKeyPair() {
        try {
            //Algorithm details
            const algorithm = {
                name: "RSA-OAEP", //Algorithm used
                modulusLength: 4096, // Key size = 4096
                publicExponent: new Uint8Array([0x01, 0x00, 0x01]), // Exponent for the RSA
                hash: "SHA-256", //Hash used
            };

            // Creating the Key Pair with the algorithm details
            const keyPair = await window.crypto.subtle.generateKey(
                algorithm,
                true, // True - Key can be extracted
                ["encrypt", "decrypt"] // Key usages
            );

            // Export the public key in SPKI format
            const spki = await window.crypto.subtle.exportKey("spki", keyPair.publicKey);
            const publicKeyPEM = arrayBufferToPEM(spki, 'PUBLIC KEY');

            // Export the private key in PKCS8 format
            const pkcs8 = await window.crypto.subtle.exportKey("pkcs8", keyPair.privateKey);
            const privateKeyPEM = arrayBufferToPEM(pkcs8, 'PRIVATE KEY');

            //Return Key Pair
            return {publicKeyPEM, privateKeyPEM};
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

        //return `-----BEGIN ${type}-----\n${formattedBase64}\n-----END ${type}-----`;
        //TODO: Validate!!
        return formattedBase64;
    };

    /**
     *
     * @param buffer
     * @returns {string}
     */
    const arrayBufferToBase64 = (buffer) => {
        let binary = '';
        const bytes = new Uint8Array(buffer);
        const len = bytes.byteLength;
        for (let i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa(binary);
    };


    const dbName = 'SRC-DATABASE';
    //const storeName = 'UserStore';

    //Function to get DB version needed to upgrade DB schema
    const getDBVersion = (dbName) => {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName);

            request.onsuccess = (event) => {
                const db = event.target.result;
                const version = db.version;
                db.close(); // Close the database connection
                resolve(version);
            };

            request.onerror = (event) => {
                reject('Database error: ' + event.target.errorCode);
            };
        });
    };

    //Function to open the indexed database
    const openDatabase = (dbName, storeName, version) => {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName, version + 1);

            request.onerror = (event) => {
                reject('Database error: ' + event.target.errorCode);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(storeName)) {
                    db.createObjectStore(storeName);
                }
            };

            request.onsuccess = (event) => {
                resolve(event.target.result);
            };
        });
    };

    //Function to store de key pair
    const saveKey = (dbName, storeName, key, keyType) => {
        let dbVersion = 0;
        getDBVersion(dbName)
            .then(version => {
                dbVersion = version;
                console.log(`Current version of the database '${dbName}': ${version}`);
                openDatabase(dbName, storeName, dbVersion).then(db => {
                    const transaction = db.transaction([storeName], 'readwrite');
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
            console.log("url: ", url);
            console.log("To: ", to);

            //TODO validar se a geração do par de chaves não deveria ser só depois de validar se é possível criar a conta
            generateRSAKeyPair().then(kPair => {
                const publicKeyPEM = kPair.publicKeyPEM;
                const privateKeyPEM = kPair.privateKeyPEM;

                console.log("Public Key:", publicKeyPEM);
                console.log("Private Key:", privateKeyPEM);

                //Save PEMKeys to IndexedDB: //Public PEM Key + //Private PEM Key
                let email = document.getElementById('register-email').value;
                saveKey(dbName, email, publicKeyPEM, 'publicKeyPEM');
                saveKey(dbName, email, privateKeyPEM, 'privateKeyPEM')

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

                    window.location.href = response.to;
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error(textStatus, errorThrown);
                });
            });
        });
    }
})();