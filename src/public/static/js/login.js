(async () => {

    const db = null;
    const dbName = 'SRC-DATABASE';
    const storeName = 'UserStore';

    // const openDatabase = (dbName, storeName) => {
    //     return new Promise((resolve, reject) => {
    //         const request = indexedDB.open(dbName, 1);
    //         request.onerror = (event) => {
    //             reject('Database error: ' + event.target.errorCode);
    //         };
    //
    //         request.onupgradeneeded = (event) => {
    //             const db = event.target.result;
    //             db.createObjectStore(storeName, {autoIncrement: true});
    //         };
    //
    //         request.onsuccess = (event) => {
    //             resolve(event.target.result);
    //         };
    //     });
    // };

    let btn = document.getElementById('search-key-btn');
    if (btn !== null) {
        btn.addEventListener('click', (e) => {
            // //const saveKey = (dbName, storeName, key, keyType) => {
            // const db = openDatabase(dbName, storeName).then(db => {
            //     const transaction = db.transaction([storeName], 'read');
            //     const objectStore = transaction.objectStore(storeName);
            //
            //     const key = objectStore.get("publicKeyPEM");
            //     if(key )
            //     document.getElementById('key-info').value = 'Valid Key Found';
            // });
            // //};
        });
    }

    btn = document.getElementById('login-btn');
    if (btn !== null) {
        btn.addEventListener('click', (e) => {
            const email = document.getElementById('login-email').value;
            if (email === undefined || email === null || email.length <= 0) {
                return;
            }

            const loginStage1Url = e.currentTarget.dataset.stateurl1;
            const loginStage2Url = e.currentTarget.dataset.stateurl2;

            if (loginStage1Url === undefined || loginStage1Url === null || loginStage1Url.length <= 0 ||
                loginStage2Url === undefined || loginStage2Url === null || loginStage2Url.length <= 0) {
                return;
            }

            //TODO: read from storage
            const publicKeyPEM = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAuLupstRTwG/UTuil9ukY\nXjVgD5DFH7scm2y7UVWBZDVlgYeSo7WetgSdgD9a85/GZMVkW6+qp7FgnqJQyCfj\nhkqCOQ/UqfAMIs0URjtq4bMKufFgemPE/c5UKbu/7MkZevbeJ7FPtcVWKdZ7Op8n\nukonh0iZg081ea0pQHmDjGS9afJTK3qojIAXDkqcaWM4AKw43kKe+N8pgPzsC4Nb\nZlnuHXhPOgYxrNqyDVSHRFLUWKm/Br0o4ccVIy2davOUNJxPQ7imYzf+fN2pkkDn\nshZjPrUM6RuJLGetpEISPDF+RoG17YLVCdo221ZMK/SMf1LcYcuItSbh2giah0cZ\ntiqUL26gFN67UwPb2r+hHQVLc5fXUy6Zgcn4IMY7hcF2A4aZ10wW8e5M/kwRCdza\ne101eh84OrgPZbzd+QRti8/5BSF/z8FxNS9FtmLPA+1bocUkPYc2rHEB97OuJh7e\nplGdHvwmBpwL8EVALd2842/Dd6WDnoD5QV9D1wFdVmLZEZqoRLeF4F8xpUSXsMp2\n3XGXh9F00ycKhKhad1jbDRphEeyn5hLL2u8RhaP2I3xEBhUZkm6AzVEjuvZ/oByP\nute5+yA+5d+RIPJ86gxUWwcaEsgbcMFvAaNbhNa6mGZ27XPN01UZHCjE5nz+XFqi\nO7RQcPc/TvDwPc3a1cc7pR8CAwEAAQ==";
            $.ajax(loginStage1Url, {
                method: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    email: email,
                    key: publicKeyPEM
                })
            }).done((response) => {
                if (!response.ok) {
                    //TODO: alert or something...
                    console.error(response.reason);
                    return;
                }

                //TODO: decipher!
                const privateKeyPEM = "----BEGIN PRIVATE KEY-----\n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\nShoqg+VjGGmbUVhGNET7mEE39HmfN5hJ\n-----END PRIVATE KEY-----";
                // response.challenge
                // window.crypto.subtle.decrypt({
                //     name: "RSA-OAEP", //Algorithm used
                //     modulusLength: 4096, // Key size = 4096
                //     publicExponent: new Uint8Array([0x01, 0x00, 0x01]), // Exponent for the RSA
                //     hash: "SHA-256", //Hash used
                // }, )

                const token = response.challenge;
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
                        //TODO: alert or something...
                        console.error(response.reason);
                        return;
                    }

                    window.location.href = response.to;
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error(textStatus, errorThrown);
                });
            }).fail((jqXHR, textStatus, errorThrown) => {
                console.error(textStatus, errorThrown);
            });
        });
    }
})();