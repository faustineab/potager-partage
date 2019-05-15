/*
 * Npm import
 */
import { createStore, applyMiddleware, compose } from 'redux';
import { persistStore, persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage';

/*
 * Local import
 */
// Reducer
import reducer from 'src/store/reducer';
import ajaxMiddleware from './ajaxMiddleware';

/*
 * Code
 */
const persistConfig = {
  key: 'root',
  storage,
  blacklist: ['password'],
};

const persistedReducer = persistReducer(persistConfig, reducer);

const appliedMiddlewares = applyMiddleware(ajaxMiddleware);

const devTools = [];
if (window.devToolsExtension) {
  devTools.push(window.devToolsExtension());
}

const enhancers = compose(appliedMiddlewares, ...devTools);


// createStore
export const store = createStore(persistedReducer, enhancers);
export const persistor = persistStore(store);
