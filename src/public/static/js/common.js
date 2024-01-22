const LocalDBName = 'SRC-DATABASE';

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

const fetchDataByKeyFromDB = (db, storeName, key) => {
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

const base64ToArrayBuffer = (base64) => {
    const binaryString = window.atob(base64);
    const len = binaryString.length;
    const bytes = new Uint8Array(len);

    for (let i = 0; i < len; i++) {
        bytes[i] = binaryString.charCodeAt(i);
    }
    return bytes.buffer;
}