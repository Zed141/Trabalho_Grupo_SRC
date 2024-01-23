(async () => {

    const openVaultModal = (e) => {
        const vaultDetailsModalElem = document.getElementById("modal-add-vault");
        if (vaultDetailsModalElem === null) {
            return;
        }
        const vaultDetailsModal = new bootstrap.Modal(vaultDetailsModalElem);

        const saveBtn = document.getElementById("save-vault-btn");
        saveBtn.dataset.action = "create";
        saveBtn.dataset.id = "";

        document.getElementById('vault-description').value = "";
        document.getElementById('vault-data').value = "";
        document.getElementById('vault-username').value = "";
        document.getElementById('vault-url').value = "";
        document.getElementById('vault-notes').value = "";

        vaultDetailsModal.show();
    };

    let btn = document.getElementById("add-vault-btn");
    if (btn !== null) {
        btn.addEventListener('click', openVaultModal);
    }

    btn = document.getElementById("add-vault-smbtn");
    if (btn !== null) {
        btn.addEventListener('click', openVaultModal);
    }

    document.querySelectorAll('.edit-btn').forEach((btn) => {
        const url1 = document.getElementById('get-vault-secret-url').value;
        const url2 = document.getElementById('details-url').value;
        const vaultDetailsModalElem = document.getElementById("modal-add-vault");
        if (vaultDetailsModalElem === null) {
            return;
        }
        const vaultDetailsModal = new bootstrap.Modal(vaultDetailsModalElem);


        btn.addEventListener('click', (e) => {

            const id = e.currentTarget.dataset.id;

            const getVaultSecretUrl = `${url1}?id=${id}`;
            $.ajax(getVaultSecretUrl, {
                method: 'GET',
                dataType: 'json',
                contentType: 'application/json',
            }).done((response) => {
                if (!response.ok) {
                    console.error(response.reason);
                    return;
                }
                console.log("response", response);
                const email = response.email;
                const encryptedNonce = base64ToArrayBuffer(response.nonce);
                const encryptedTag = base64ToArrayBuffer(response.tag);
                const encryptedSecret = base64ToArrayBuffer(response.secret);

                // Fetch Public Key from the Indexed DB
                getDBVersion(LocalDBName)
                    .then(version => {
                        openDatabase(LocalDBName, email, version)
                            .then(db => {
                                fetchDataByKeyFromDB(db, email, "kPair")
                                    .then(keyPair => {
                                        window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedNonce)
                                            .then(nonceDecrypted =>{
                                                const nonce = arrayBufferToBase64(nonceDecrypted);

                                                window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedTag)
                                                    .then(tagDecrypted =>{
                                                        const tag = arrayBufferToBase64(tagDecrypted);

                                                    window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedSecret)
                                                        .then(secretDecrypted =>{
                                                            const secret = new TextDecoder().decode(new Uint8Array(secretDecrypted));


                                                            const detailsUrl = `${url2}?id=${id}`;
                                                            $.ajax(detailsUrl, {
                                                                method: 'POST',
                                                                dataType: 'json',
                                                                contentType: 'application/json',
                                                                data: JSON.stringify({
                                                                    nonce: nonce,
                                                                    tag: tag,
                                                                    secret: btoa(secret),

                                                                })
                                                            }).done((response) => {
                                                                if (!response.ok) {
                                                                    console.error(response.reason);
                                                                    return;
                                                                }
                                                                console.log("repsonse: ", response);
                                                                document.getElementById('vault-data').value = response.data;
                                                                document.getElementById('vault-description').value = response.description;
                                                                document.getElementById('vault-username').value = response.username;
                                                                document.getElementById('vault-url').value = response.url;
                                                                document.getElementById('vault-notes').value = response.notes;

                                                                const saveBtn = document.getElementById("save-vault-btn");
                                                                saveBtn.dataset.action = "update";
                                                                saveBtn.dataset.id = response.id;

                                                                vaultDetailsModal.show();
                                                            })


                                                    })
                                                }


                                            )
                                    })
                            })
                    })

                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error(textStatus, errorThrown);
                });

            })


        });
    });

    btn = document.getElementById("save-vault-btn");
    if (btn !== null) {
        btn.addEventListener("click", (e) => {
            const description = document.getElementById("vault-description").value;
            const data = document.getElementById("vault-data").value;
            const username = document.getElementById("vault-username").value;
            const url = document.getElementById("vault-url").value;
            const notes = document.getElementById("vault-notes").value;

            const action = e.currentTarget.dataset.action;
            if (action !== "create" && action !== 'update') {
                console.error("Invalid vault action, missing data attribute in save button");
                return;
            }

            if (description === undefined || description === null || description.length <= 0) {
                return;
            }

            if (action === "create" && (data === undefined || data === null || data.length <= 0)) {
                return;
            }


            const actionUrl = action === "create" ? e.currentTarget.dataset.createurl : e.currentTarget.dataset.updateurl;
            if (actionUrl === undefined || actionUrl === null || actionUrl.length <= 0) {
                return;
            }

            $.ajax(actionUrl, {
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    id: e.currentTarget.dataset.id,
                    description: description,
                    data: data,
                    username: username,
                    url: url,
                    notes: notes
                })
            }).done((response) => {
                if (!response.ok) {
                    console.error(response.reason);
                    return;
                }

                //NOTE: just refresh, could be improved
                window.location.reload();
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }
})();