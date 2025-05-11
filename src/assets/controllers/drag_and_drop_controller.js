import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['scope', 'task'];

    connect() {
        console.log("Drag and Drop Controller connected");

        this.scopeTargets.forEach(scope => {
            scope.addEventListener('dragover', this.dragOver.bind(this));
            scope.addEventListener('drop', this.drop.bind(this));
        });

        this.taskTargets.forEach(task => {
            task.addEventListener('dragstart', this.dragStart.bind(this));
        });

        // ✅ Kontrola při načtení stránky
        this.checkEmptyTables();
    }

    dragStart(event) {
        // Pokud je kliknuto na odkaz, neaktivujeme drag & drop
        if (event.target.closest("a[data-no-drag]")) {
            console.log("Drag blocked: Link clicked");
            return;
        }
    
        const taskId = event.target.closest("tr").dataset.taskId;
        event.dataTransfer.setData("text/plain", taskId);
        console.log("Dragging task:", taskId);
    }

    dragOver(event) {
        event.preventDefault(); // Umožní drop
    }

    drop(event) {
        event.preventDefault();

        const taskId = event.dataTransfer.getData('text/plain');
        console.log("Dropped Task ID:", taskId);

        const newScope = event.currentTarget.dataset.scope;
        console.log("New Scope:", newScope);

        // Najdeme task element
        const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);

        if (taskElement) {
            console.log("Moving task:", taskElement);

            // Najdeme tbody v cílové tabulce
            const targetTable = event.currentTarget.querySelector('tbody');

            // ❗❗ Skryjeme placeholder, pokud existuje
            const placeholder = targetTable.querySelector('[data-placeholder="true"]');
            if (placeholder) {
                placeholder.style.display = 'none';
            }

            // Přesuneme task do tabulky
            targetTable.appendChild(taskElement);

            // Odeslání na server
            this.updateTaskScope(taskId, newScope);

            // ❗ Zkontrolujeme prázdné tabulky po přesunu úkolu
            this.checkEmptyTables();
        } else {
            console.error("Task element not found");
        }
    }

    checkEmptyTables() {
        console.log("Checking empty tables...");

        setTimeout(() => {
            this.scopeTargets.forEach(scope => {
                const tbody = scope.querySelector('tbody');
                const tasks = tbody.querySelectorAll('.task-item');
                const placeholder = tbody.querySelector('[data-placeholder="true"]');

                console.log(`Table ${scope.dataset.scope} has ${tasks.length} tasks`);

                if (tasks.length === 0 && placeholder) {
                    console.log("Showing placeholder...");
                    placeholder.style.display = 'table-row';
                } else if (tasks.length > 0 && placeholder) {
                    console.log("Hiding placeholder...");
                    placeholder.style.display = 'none';
                }
            });
        }, 100); // Timeout umožní DOM aktualizaci
    }

    async updateTaskScope(taskId, newScope) {
        console.log("Updating task scope:", taskId, newScope);
    
        try {
            const response = await fetch('/task/update-scope', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ taskId, newScope }),
            });
    
            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }
    
            // Najdeme, do které sekce úkol patří
            const listingType = document.querySelector("#tasksCurrentContainer").dataset.listingType;
            
            // Aktualizujeme správný listing
            this.reloadListingTables(listingType);
    
        } catch (error) {
            console.error("Failed to update task scope", error);
        }
    }
    
    async reloadListingTables(listingType) {
        try {
            // ✅ Zavoláme AJAX jen pro daný listing (personal/work)
            const response = await fetch(`/listing/${listingType}/tables`);
            if (!response.ok) {
                throw new Error(`Failed to reload ${listingType} tasks`);
            }
    
            const html = await response.text();
    
            // ✅ Vytvoříme dočasný kontejner pro zpracování nové HTML odpovědi
            const tempDiv = document.createElement("div");
            tempDiv.innerHTML = html;
    
            // ✅ Nahradíme pouze tabulky uvnitř existujících kontejnerů
            document.querySelector("#tasksCurrentContainer").innerHTML =
                tempDiv.querySelector("#tasksCurrentContainer").innerHTML;
            document.querySelector("#tasksNextContainer").innerHTML =
                tempDiv.querySelector("#tasksNextContainer").innerHTML;
    
            console.log("Listing tables reloaded");
    
            // ❗ Po aktualizaci znovu přidáme drag & drop event listenery!
            this.scopeTargets.forEach(scope => {
                scope.addEventListener('dragover', this.dragOver.bind(this));
                scope.addEventListener('drop', this.drop.bind(this));
            });
    
            this.taskTargets.forEach(task => {
                task.addEventListener('dragstart', this.dragStart.bind(this));
            });
    
            // ❗ Zkontrolujeme prázdné tabulky
            this.checkEmptyTables();
    
        } catch (error) {
            console.error("Error reloading tables:", error);
        }
    }
}