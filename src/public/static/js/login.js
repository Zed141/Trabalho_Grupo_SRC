(async () => {

    const db = null;
    const dbName = 'SRC-DATABASE';

    const pemToArrayBuffer = (pemKey) => {
        const binary = atob(pemKey.replace('-----BEGIN PRIVATE KEY-----', '')
            .replace('-----END PRIVATE KEY-----', '')
            .replace(/\n/g, ''));

        const len = binary.byteLength;
        const bytes = new Uint8Array(len);

        for (let i = 0; i < len; i++) {
            let char = binary.charCodeAt(i);
            bytes.push(char >>> 8);
            bytes.push(char & 0xFF);
        }

        return bytes;
    };

    // Function to decode base64 to ArrayBuffer
    function base64ToArrayBuffer(base64) {
        // Ensure base64 is a string
        if (typeof base64 !== 'string') {
            console.error('Expected a string for base64, but received:', typeof base64);
            throw new TypeError('base64 must be a string');
        }

        // Clean the base64 string
        const cleanedBase64 = base64.replace(/[\r\n]+/g, '').trim();

        try {
            const binaryString = window.atob(cleanedBase64);
            const bytes = new Uint8Array(binaryString.length);
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            return bytes.buffer;
        } catch (e) {
            console.error("Error converting base64 to ArrayBuffer:", e);
            throw e;
        }
    }


    async function decryptData(privateKey, encryptedData) {
        const encryptedArrayBuffer = base64ToArrayBuffer(encryptedData);
        try {
            const decryptedBuffer = await window.crypto.subtle.decrypt(
                {
                    name: "RSA-OAEP"
                },
                privateKey,
                encryptedArrayBuffer
            );
            return new TextDecoder().decode(decryptedBuffer);
        } catch (error) {
            console.error("Decryption failed:", error);
        }
    }

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

    const openDatabase = (dbName, storeName, version) => {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(dbName, version);
            request.onerror = (event) => {
                reject('Database error: ' + event.target.errorCode);
            };

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                db.createObjectStore(storeName, {autoIncrement: true});
            };

            request.onsuccess = (event) => {
                resolve(event.target.result);
            };
        });
    };

    function fetchDataByKeyFromDB(db, storeName, key) {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([storeName], 'readonly');
            const objectStore = transaction.objectStore(storeName);
            const request = objectStore.get(key,); // Fetches data by key

            request.onerror = (event) => {
                reject('Error fetching data: ' + event.target.errorCode);
            };

            request.onsuccess = (event) => {
                resolve(event.target.result); // Resolves with the fetched data
            };
        });
    }

    let btn = document.getElementById('login-btn');
    if (btn !== null) {
        btn.addEventListener('click', (e) => {

            let publicKeyPEM = '';
            const email = document.getElementById('login-email').value;
            if (email === undefined || email === null || email.length <= 0) {
                return;
            } else {

            }

            const loginStage1Url = e.currentTarget.dataset.stage1url;
            const loginStage2Url = e.currentTarget.dataset.stage2url;

            if (loginStage1Url === undefined || loginStage1Url === null || loginStage1Url.length <= 0 ||
                loginStage2Url === undefined || loginStage2Url === null || loginStage2Url.length <= 0) {
                return;
            }

            // Fetch Public Key from the Indexed DB
            getDBVersion(dbName)
                .then(version => {
                    openDatabase(dbName, email, version)
                        .then(db => {
                            //fetchDataByKeyFromDB(db, email, "publicKeyPEM")
                            fetchDataByKeyFromDB(db, email, "kPair")
                                .then(keyPair => {
                                    console.log(keyPair);
                                    $.ajax(loginStage1Url, {
                                        method: 'POST',
                                        dataType: 'json',
                                        contentType: 'application/json',
                                        data: JSON.stringify({
                                            email: email
                                        })
                                    }).done((response) => {
                                        if (!response.ok) {
                                            console.error(response.reason);
                                            return;
                                        }



                                        console.log("cipheredb64", ciphered);
                                        //const ciphered = atob(response.challenge);
                                        if (ciphered.length <= 0) {
                                            console.log('Ciphered é inválido!');
                                            return;
                                        }

                                        const cipheredArrayBuffer = base64ToArrayBuffer(ciphered);
                                        console.log("cipheredbuffer", cipheredArrayBuffer);

                                        debugger;

                                        decryptData(keyPair.privateKey, cipheredArrayBuffer).then(
                                            decryptedText => console.log("Decrypted Text: ", decryptedText)
                                        ).catch(console.error);

                                        debugger;










                                        const buffer = new TextEncoder().encode(ciphered).buffer;
                                        console.log("ciphered: ", ciphered);
                                        console.log("buffer: ", buffer);

                                        console.log(keyPair.privateKey instanceof CryptoKey);
                                        console.log(keyPair.privateKey.usages);
                                        console.log(buffer instanceof ArrayBuffer);
                                        window.crypto.subtle.decrypt(
                                            {
                                            name: "RSA-OAEP",
                                            }
                                        , keyPair.privateKey, buffer)
                                            .then(token => {
                                                console.log(decryptData);
                                                debugger;
                                                $.ajax(loginStage2Url, {
                                                    method: 'POST',
                                                    dataType: 'json',
                                                    contentType: 'application/json',
                                                    data: JSON.stringify({
                                                        email: email,
                                                        token: token
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
                                            }).catch(error => {
                                            console.error('Error while decrypting data:', error);
                                        });

                                    }).fail((jqXHR, textStatus, errorThrown) => {
                                        console.error(textStatus, errorThrown);
                                    });
                                })
                                .catch(error => {
                                    console.error('Error while fetching data:', error);
                                });
                        })
                        .catch(error => {
                            console.error('Failed to open database:', error);
                        })
                });
        });
    }
})();