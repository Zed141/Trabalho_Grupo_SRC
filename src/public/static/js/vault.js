(async () => {

    const lastAccessDetails = {
        nonce: null,
        tag: null,
        secret: null
    };

    const openVaultModal = (e) => {
        const newVaultModalElem = document.getElementById("modal-create-vault");
        if (newVaultModalElem === null) {
            return;
        }
        const newVaultModal = bootstrap.Modal.getOrCreateInstance(newVaultModalElem);

        document.getElementById('c-vault-description').value = "";
        document.getElementById('c-vault-data').value = "";
        document.getElementById('c-vault-username').value = "";
        document.getElementById('c-vault-url').value = "";
        document.getElementById('c-vault-notes').value = "";

        newVaultModal.show();
    };

    let btn = document.getElementById("open-create-vault-btn");
    if (btn !== null) {
        btn.addEventListener('click', openVaultModal);
    }

    btn = document.getElementById("open-create-vault-smbtn");
    if (btn !== null) {
        btn.addEventListener('click', openVaultModal);
    }

    document.querySelectorAll('.edit-btn').forEach((btn) => {
        const url1 = document.getElementById('vault-secret-url').value;
        const url2 = document.getElementById('details-url').value;

        const vaultDetailsModalElem = document.getElementById("modal-edit-vault");
        if (vaultDetailsModalElem === null) {
            return;
        }
        const vaultDetailsModal = bootstrap.Modal.getOrCreateInstance(vaultDetailsModalElem);

        btn.addEventListener('click', (e) => {
            lastAccessDetails.nonce = null;
            lastAccessDetails.tag = null;
            lastAccessDetails.secret = null;

            const id = e.currentTarget.dataset.id;
            const getVaultSecretUrl = `${url1}?id=${id}`;
            const detailsUrl = `${url2}?id=${id}`;
            $.ajax(getVaultSecretUrl, {
                method: 'GET',
                dataType: 'json',
                contentType: 'application/json',
            }).done((response) => {
                if (!response.ok) {
                    alert(response.reason);
                    return;
                }

                const email = response.email;
                const encryptedNonce = base64ToArrayBuffer(response.nonce);
                const encryptedTag = base64ToArrayBuffer(response.tag);
                const encryptedSecret = base64ToArrayBuffer(response.secret);

                getDBVersion(LocalDBName)
                    .then(version => {
                        openDatabase(LocalDBName, email, version)
                            .then(db => {
                                fetchDataByKeyFromDB(db, email, "kPair")
                                    .then(keyPair => {
                                        window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedNonce)
                                            .then(decryptedNonce => {
                                                const nonce = arrayBufferToBase64(decryptedNonce);
                                                window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedTag)
                                                    .then(decryptedTag => {
                                                        const tag = arrayBufferToBase64(decryptedTag);
                                                        window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedSecret)
                                                            .then(decryptedSecret => {
                                                                const secret = btoa(new TextDecoder().decode(new Uint8Array(decryptedSecret)));

                                                                $.ajax(detailsUrl, {
                                                                    method: 'POST',
                                                                    dataType: 'json',
                                                                    contentType: 'application/json',
                                                                    data: JSON.stringify({
                                                                        nonce: nonce,
                                                                        tag: tag,
                                                                        secret: secret,
                                                                    })
                                                                }).done((response) => {
                                                                    if (!response.ok) {
                                                                        alert(response.reason);
                                                                        return;
                                                                    }

                                                                    //document.getElementById("v-nonce").value = nonce;
                                                                    //document.getElementById("v-tag").value = tag;
                                                                    //document.getElementById("v-sec").value = secret;

                                                                    lastAccessDetails.nonce = nonce;
                                                                    lastAccessDetails.tag = tag;
                                                                    lastAccessDetails.secret = secret;

                                                                    document.getElementById('e-vault-data').value = response.data;
                                                                    document.getElementById('e-vault-description').value = response.description;
                                                                    document.getElementById('e-vault-username').value = response.username;
                                                                    document.getElementById('e-vault-url').value = response.url;
                                                                    document.getElementById('e-vault-notes').value = response.notes;

                                                                    const saveBtn = document.getElementById("edit-vault-btn");
                                                                    saveBtn.dataset.id = response.id;

                                                                    vaultDetailsModal.show();
                                                                }).fail((jqXHR, textStatus, errorThrown) => {
                                                                    console.error(textStatus, errorThrown);
                                                                });
                                                            });
                                                    });
                                            });
                                    }).catch(error => { // ./fetchDataByKeyFromDB
                                    console.error('Error while fetching data:', error);
                                });
                            }).catch(error => { // ./openDatabase
                            console.error('Error while fetching data:', error);
                        });
                    }).catch(error => { // ./getDBVersion
                    console.error('Error while fetching data:', error);
                });
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });//./add click event
    });

    btn = document.getElementById("create-vault-btn");
    if (btn !== null) {
        btn.addEventListener("click", (e) => {
            const description = document.getElementById("c-vault-description").value;
            const data = document.getElementById("c-vault-data").value;
            const username = document.getElementById("c-vault-username").value;
            const url = document.getElementById("c-vault-url").value;
            const notes = document.getElementById("c-vault-notes").value;

            if (description === undefined || description === null || description.length <= 0) {
                return;
            }

            if (data === undefined || data === null || data.length <= 0) {
                return;
            }

            const createUrl = e.currentTarget.dataset.url;
            if (createUrl === undefined || createUrl === null || createUrl.length <= 0) {
                return;
            }

            $.ajax(createUrl, {
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    description: description,
                    data: data,
                    username: username,
                    url: url,
                    notes: notes
                })
            }).done((response) => {
                if (!response.ok) {
                    alert(response.reason);
                    return;
                }

                //NOTE: just refresh, could be improved
                window.location.reload();
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }

    btn = document.getElementById("edit-vault-btn");
    if (btn !== null) {
        btn.addEventListener("click", (e) => {
            const description = document.getElementById("e-vault-description").value;
            const data = document.getElementById("e-vault-data").value;
            const username = document.getElementById("e-vault-username").value;
            const url = document.getElementById("e-vault-url").value;
            const notes = document.getElementById("e-vault-notes").value;

            if (description === undefined || description === null || description.length <= 0) {
                return;
            }

            if (data === undefined || data === null || data.length <= 0) {
                return;
            }

            const updateUrl = e.currentTarget.dataset.url;
            if (updateUrl === undefined || updateUrl === null || updateUrl.length <= 0) {
                return;
            }

            $.ajax(updateUrl, {
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    id: e.currentTarget.dataset.id,
                    description: description,
                    data: data,
                    username: username,
                    url: url,
                    notes: notes,
                    tag: lastAccessDetails.tag,
                    nonce: lastAccessDetails.nonce,
                    secret: lastAccessDetails.secret
                })
            }).done((response) => {
                if (!response.ok) {
                    alert(response.reason);
                    return;
                }

                //NOTE: just refresh, could be improved
                window.location.reload();
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }

    btn = document.getElementById("share-vault-btn");
    if (btn !== null) {
        btn.addEventListener("click", (e) => {
            const shareUrl = e.currentTarget.dataset.url;
            const id = e.currentTarget.dataset.id;
            if (shareUrl === undefined || shareUrl === null || shareUrl.length <= 0) {
                return;
            }

            const selectedElem = document.querySelector(".share-chk:checked");
            if (selectedElem === null) {
                return;
            }

            const userId = selectedElem.value;
            const url = `${shareUrl}?vid=${id}&uid=${userId}`;
            $.ajax(url, {
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    tag: lastAccessDetails.tag,
                    nonce: lastAccessDetails.nonce,
                    secret: lastAccessDetails.secret,
                })
            }).done((response) => {
                if (!response.ok) {
                    alert(response.reason);
                    return;
                }

                //NOTE: just refresh, could be improved
                alert(response.message);
                window.location.reload();
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }

    document.querySelectorAll('.share-btn').forEach((btn) => {
        const shareVaultModalElem = document.getElementById("modal-share-vault");
        if (shareVaultModalElem === null) {
            return;
        }

        const shareVaultModal = bootstrap.Modal.getOrCreateInstance(shareVaultModalElem);
        btn.addEventListener('click', (e) => {
            const url1 = document.getElementById('vault-secret-url').value;

            lastAccessDetails.nonce = null;
            lastAccessDetails.tag = null;
            lastAccessDetails.secret = null;

            const shareVaultModalElem = document.getElementById("modal-share-vault");
            if (shareVaultModalElem === null) {
                return;
            }

            const shareVaultModal = bootstrap.Modal.getOrCreateInstance(shareVaultModalElem);
            const shareBtn = document.getElementById("share-vault-btn");

            const id = e.currentTarget.dataset.id;
            const getVaultSecretUrl = `${url1}?id=${id}`;
            $.ajax(getVaultSecretUrl, {
                method: 'GET',
                dataType: 'json',
                contentType: 'application/json',
            }).done((response) => {
                if (!response.ok) {
                    alert(response.reason);
                    return;
                }

                const email = response.email;
                const encryptedNonce = base64ToArrayBuffer(response.nonce);
                const encryptedTag = base64ToArrayBuffer(response.tag);
                const encryptedSecret = base64ToArrayBuffer(response.secret);

                getDBVersion(LocalDBName)
                    .then(version => {
                        openDatabase(LocalDBName, email, version)
                            .then(db => {
                                fetchDataByKeyFromDB(db, email, "kPair")
                                    .then(keyPair => {
                                        window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedNonce)
                                            .then(decryptedNonce => {
                                                const nonce = arrayBufferToBase64(decryptedNonce);
                                                window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedTag)
                                                    .then(decryptedTag => {
                                                        const tag = arrayBufferToBase64(decryptedTag);
                                                        window.crypto.subtle.decrypt({name: "RSA-OAEP"}, keyPair.privateKey, encryptedSecret)
                                                            .then(decryptedSecret => {
                                                                const secret = btoa(new TextDecoder().decode(new Uint8Array(decryptedSecret)));

                                                                const usersUrl = document.getElementById('users-url').value;
                                                                $.ajax(`${usersUrl}?vid=${id}`, {
                                                                    method: 'GET',
                                                                    dataType: 'json',
                                                                    contentType: 'application/json',
                                                                }).done((response) => {
                                                                    if (!response.ok) {
                                                                        alert(response.reason);
                                                                        return;
                                                                    }

                                                                    lastAccessDetails.nonce = nonce;
                                                                    lastAccessDetails.tag = tag;
                                                                    lastAccessDetails.secret = secret;

                                                                    let lines = [];
                                                                    const max = response.users.length;
                                                                    for (let i = 0; i < max; i++) {
                                                                        let user = response.users[i];

                                                                        let userAvatar = user.avatar.content;
                                                                        let userName = user.name;
                                                                        let userEmail = user.email;
                                                                        let userId = user.id;

                                                                        if (user.avatar.img) {
                                                                            userAvatar = `<span class="avatar" style="background-image: url(${user.avatar.content})"></span>`;
                                                                        }

                                                                        let ctrl = `<input type="radio" name="sharewith" class="share-chk" id="shared-with-${userId}" value="${userId}">`;
                                                                        if(user.shared) {
                                                                            ctrl = '<button type="button" class="btn btn-sm btn-ghost-danger">Revoke</button>'
                                                                        }

                                                                        lines.push(`<div><div class="row"><div class="col-auto"><span class="avatar">${userAvatar}</span></div>
                            <div class="col"><div class="text-truncate"><strong>${userName}</strong></div><div class="text-secondary">${userEmail}</div></div>
                            <div class="col-auto align-self-center">${ctrl}</div></div></div>`);
                                                                    }

                                                                    shareBtn.dataset.id = id;
                                                                    document.getElementById('vault-users-list').innerHTML = lines.join('');
                                                                    shareVaultModal.show();
                                                                }).fail((jqXHR, textStatus, errorThrown) => {
                                                                    console.error(textStatus, errorThrown);
                                                                });
                                                            });
                                                    });
                                            });
                                    }).catch(error => { // ./fetchDataByKeyFromDB
                                    console.error('Error while fetching data:', error);
                                });
                            }).catch(error => { // ./openDatabase
                            console.error('Error while fetching data:', error);
                        });
                    }).catch(error => { // ./getDBVersion
                    console.error('Error while fetching data:', error);
                });
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });// ./click event
    });
})();