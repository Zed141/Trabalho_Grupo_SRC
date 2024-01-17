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

        // Export the public and private key in JWK format (JSON web key)
        const publicKey = await window.crypto.subtle.exportKey("jwk", keyPair.publicKey);
        const privateKey = await window.crypto.subtle.exportKey("jwk", keyPair.privateKey);

        //Return Key Pair
        return { publicKey, privateKey };

        //Error catching
    } catch (error) {
        console.error("Key generation failed:", error);
    }
}

//Running the generate RSAKeyPair and log in the console for test purposes
generateRSAKeyPair().then(keyPair => {
    console.log("Public Key:", keyPair.publicKey);
    console.log("Private Key:", keyPair.privateKey);
});