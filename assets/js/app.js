// assets/js/app.js - COMPLETE JAVASCRIPT FROM ORIGINAL FILE
const DB = {
    async getAll(table) {
        const res = await fetch(`api.php/${table}`);
        return await res.json();
    },
    // ... rest of DB functions
};

// All other JavaScript functions...