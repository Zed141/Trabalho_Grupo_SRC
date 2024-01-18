/*KEY PAR GENERATION AND CONVERSION TO PEM*/

//Function to generate RSA Public and Private Key pair
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
        return { publicKeyPEM, privateKeyPEM };

        //Error catching
    } catch (error) {
        console.error("Key generation failed:", error);
    }
}

function arrayBufferToPEM(buffer, type) {
    const base64 = arrayBufferToBase64(buffer);
    const formattedBase64 = base64.match(/.{1,64}/g).join('\n');
    const pem = `-----BEGIN ${type}-----\n${formattedBase64}\n-----END ${type}-----`;

    return pem;
}

function arrayBufferToBase64(buffer) {
    var binary = '';
    var bytes = new Uint8Array(buffer);
    var len = bytes.byteLength;
    for (var i = 0; i < len; i++) {
        binary += String.fromCharCode(bytes[i]);
    }
    return window.btoa(binary);
}
/*-------------------------------------------------------------------------*/


/*DATABASE FUNCTIONS*/
const dbName = 'SRC-DATABASE';
const storeName = 'UserStore';

//Function to open the indexed database
function openDatabase(dbName, storeName) {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(dbName, 1);

        request.onerror = (event) => {
            reject('Database error: ' + event.target.errorCode);
        };

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            db.createObjectStore(storeName, { autoIncrement: true });
        };

        request.onsuccess = (event) => {
            resolve(event.target.result);
        };
    });
}

//Function to store de key pair
async function saveKey(dbName, storeName, key, keyType) {
    const db = await openDatabase(dbName, storeName);
    return new Promise((resolve, reject) => {
        const transaction = db.transaction([storeName], 'readwrite');
        const objectStore = transaction.objectStore(storeName);
        const request = objectStore.put(key, keyType);

        request.onerror = (event) => {
            reject('Error writing data: ' + event.target.errorCode);
        };

        request.onsuccess = (event) => {
            resolve(event.target.result);
        };
    });
}

/*-------------------------------------------------------------------------*/


//Running the generate RSAKeyPair and log in the console for test purposes

generateRSAKeyPair().then(keyPair => {
    //Generate Key Pair
    const publicKeyPEM = keyPair.publicKeyPEM;
    const privateKeyPEM = keyPair.privateKeyPEM;
    console.log("Public Key:", publicKeyPEM);
    console.log("Private Key:", privateKeyPEM);

    //Save PEMKeys to IndexedDB
    //Public PEM Key
    saveKey(dbName, storeName, publicKeyPEM, 'publicKeyPEM')
        .then((id) => console.log('Public key saved with ID:', id))
        .catch((error) => console.error(error));

    //Private PEM Key
    saveKey(dbName, storeName, privateKeyPEM, 'privateKeyPEM')
        .then((id) => console.log('Private key saved with ID:', id))
        .catch((error) => console.error(error));
})
    .catch((error) => {
        console.error("Error generating key pair:", error);
    });

