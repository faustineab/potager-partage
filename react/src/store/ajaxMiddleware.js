import axios from 'axios';

import {
  FETCH_GARDENLIST,
  JOIN_GARDEN,
  receivedGardenList,
} from 'src/store/reducer';

const ajaxMiddleware = store => next => (action) => {
  switch (action.type) {
    case FETCH_GARDENLIST:
      next(action);

      axios.get('http://localhost/Projet/potager-partage/symfo/public/register/user')
        .then((response) => {
          // console.log('response data', response.data);
          const gardenList = response.data.map(garden => ({
            key: garden.id,
            text: garden.name,
            value: garden.id,
          }));
          store.dispatch(receivedGardenList(gardenList));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    case JOIN_GARDEN:
      next(action);

      axios.post('http://localhost/Projet/potager-partage/symfo/public/register/user', {
        name: `${store.getState().firstName} ${store.getState().lastName}`,
        gardenId: store.getState().gardenId,
        email: store.getState().email,
        password: store.getState().password,
        phone: store.getState().phoneNumber,
        address: store.getState().address,
      })
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    default:
      next(action);
      break;
  }
};

export default ajaxMiddleware;
