import { startStimulusApp } from '@symfony/stimulus-bundle';

const app = startStimulusApp();
// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
app.register('search', import('./controllers/search_controller.js'));
app.register('drag-and-drop', import('./controllers/drag_and_drop_controller.js'));