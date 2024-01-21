(async () => {
    //const vaultDetailsModal = document.getElementById('modal-add-vault');


    document.querySelectorAll('.edit-btn').forEach((btn) => {
        const url = document.getElementById('details-url').value;
        btn.addEventListener('click', (e) => {


            const id = e.currentTarget.dataset.id;
            const detailsUrl = `${url}?id=${id}`;
            $.ajax(url, {
                method: 'GET',
                dataType: 'json',
                contentType: 'application/json',
            }).done((response) => {
                if (!response.ok) {
                    console.error(response.reason);
                    return;
                }

                document.getElementById('vault-description').value = response.descrition;
                document.getElementById('vault-data').value = response.data; //TODO?
                document.getElementById('vault-username').value = response.username;
                document.getElementById('vault-url').value = response.url;
                document.getElementById('vault-notes').value = response.notes;

                const vaultDetailsModal = document.getElementById('modal-add-vault');
                vaultDetailsModal.show();
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    });

    const btn = document.getElementById('btn-add-vault');
    if (btn !== null) {
        btn.addEventListener('click', (e) => {
            const description = document.getElementById('vault-description').value;
            const data = document.getElementById('vault-data').value;
            const username = document.getElementById('vault-username').value;
            const url = document.getElementById('vault-url').value;
            const notes = document.getElementById('vault-notes').value;

            if (description === undefined || description === null || description.length <= 0) {
                return;
            }

            if (data === undefined || data === null || data.length <= 0) {
                return;
            }

            const action = e.currentTarget.dataset.action;
            if (action !== 'create' && action !== 'update') {
                console.error('Invalid vault action, missing data attribute in save button');
                return;
            }

            const actionUrl = action === 'create' ? e.currentTarget.dataset.createurl : e.currentTarget.dataset.updateurl;
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
                window.local.reload();
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }
})();