(async () => {

    let btn = document.getElementById('login-btn');
    if (btn !== null) {
        btn.addEventListener('click', (e) => {
            const email = document.getElementById('login-email').value;
            if (email === undefined || email === null || email.length <= 0) {
                return;
            }

            const loginStage1Url = e.currentTarget.dataset.stage1url;
            const loginStage2Url = e.currentTarget.dataset.stage2url;

            if (loginStage1Url === undefined || loginStage1Url === null || loginStage1Url.length <= 0 ||
                loginStage2Url === undefined || loginStage2Url === null || loginStage2Url.length <= 0) {
                return;
            }

            // Fetch Public Key from the Indexed DB
            getDBVersion(LocalDBName)
                .then(version => {
                    openDatabase(LocalDBName, email, version)
                        .then(db => {
                            fetchDataByKeyFromDB(db, email, "kPair")
                                .then(keyPair => {

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

                                        const buffer = base64ToArrayBuffer(response.challenge);
                                        window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, buffer)
                                            .then(token => {
                                                const secret = new TextDecoder().decode(new Uint8Array(token));
                                                console.log('Got MTF Token!', secret);
                                                $.ajax(loginStage2Url, {
                                                    method: 'POST',
                                                    dataType: 'json',
                                                    contentType: 'application/json',
                                                    data: JSON.stringify({
                                                        email: email,
                                                        token: secret
                                                    })
                                                }).done((response) => {
                                                    if (!response.ok) {
                                                        console.error(response.reason);
                                                        return;
                                                    }

                                                    window.location.replace(response.to);
                                                }).fail((jqXHR, textStatus, errorThrown) => {
                                                    console.error(textStatus, errorThrown);
                                                }); //./ajax call 2
                                            }).catch(error => {
                                            console.error('Error while decrypting data:', error);
                                        });

                                    }).fail((jqXHR, textStatus, errorThrown) => {
                                        console.error(textStatus, errorThrown);
                                    }); // ./ajax call 1

                                }).catch(error => {
                                console.error('Error while fetching data:', error);
                            });
                        }).catch(error => {
                        console.error('Failed to open database:', error);
                    })
                });
        });
    }
})();