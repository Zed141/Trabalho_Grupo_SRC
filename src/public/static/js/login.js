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
                            fetchDataByKeyFromDB(db, email, "publicKeyPEM")
                                .then(data => {
                                    return;

                                    //TODO: REVER E REATIVAR
                                    console.log("data", data);
                                    publicKeyPEM = data;
                                    //In case the public key is missing from the Indexed DB has to be fetched from the Database
                                    console.log(publicKeyPEM);
                                    //TODO Não consigo fazer o pedido à DB (adicionei uma action no app controller)
                                    //TODO: CONFIRMAR!
                                    if (publicKeyPEM.length === 0) {
                                        $.ajax('/app/get-public-pem', {
                                            method: 'POST',
                                            dataType: 'json',
                                            contentType: 'application/json',
                                            data: JSON.stringify({
                                                email: email,
                                            })
                                        }).done((response) => {
                                            if (response.ok) {
                                                console.log(response, response.data);
                                            }
                                        });
                                    }
                                })
                                .catch(error => {
                                    console.error('Error while fetching data:', error);
                                });
                        })
                        .catch(error => {
                            console.error('Failed to open database:', error);
                        })
                });

            $.ajax(loginStage1Url, {
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email
                })
            }).done((response) => {
                if (!response.ok) {
                    //TODO: alert or something...
                    console.error(response.reason);
                    return;
                }

                const ciphered = atob(response.challenge);
                if (ciphered.length <= 0) {
                    //TODO: ERROR!
                    return;
                }

                getDBVersion(dbName)
                    .then(version => {
                        openDatabase(dbName, email, version)
                            .then(db => {
                                fetchDataByKeyFromDB(db, email, "privateKeyPEM")
                                    .then(storedPKey => {

                                        const pKey = pemToArrayBuffer(storedPKey);
                                        window.crypto.subtle.decrypt({
                                            name: "RSA-OAEP",
                                            modulusLength: 4096,
                                            publicExponent: new Uint8Array([0x01, 0x00, 0x01]),
                                            hash: "SHA-256"
                                        }, pKey, ciphered).then(token => {
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
                                        });

                                    }).catch(error => {
                                    console.error('Error while fetching data:', error);
                                });
                            })
                            .catch(error => {
                                console.error('Failed to open database:', error);
                            })
                    });
                //./getDBVersion para acesso à privada
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }
})();