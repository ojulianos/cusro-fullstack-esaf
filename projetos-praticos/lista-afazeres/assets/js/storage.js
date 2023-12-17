class StorageData {

    constructor (key) {
        this.storage = false;
        if (!localStorage.getItem(key)) {
            localStorage.setItem(key, '')
            this.storage = localStorage.getItem(key);
            this.key = key;
        }
    }

    hasStorage() {
        return this.storage.trim().length > 0;
    }

    getAll() {
        if (this.hasStorage()) {
            return JSON.parse(this.storage);
        }
        return itemList;
    }

    getItemm(id) {
        if (this.hasStorage()) {
            return JSON.parse(this.storage)[id];
        }
    }

    deleteItem(id) {
        if (this.hasStorage()) {
            let item = JSON.parse(this.storage).pop(id);
            localStorage.setItem(this.key, JSON.stringify(item));
        }
    }

}