import axios from 'axios';

import {
  FETCH_GARDENLIST,
  receivedGardenList,
} from 'src/store/reducer';

const ajaxMiddleware = store => next => (action) => {
  switch (action.type) {
    case FETCH_GARDENLIST:
      next(action);

      axios.get('http://localhost/Projet/potager-partage/symfo/public/register/user')
        .then((response) => {
          console.log(response.data);
          const gardenList = response.data.map(garden => ({
            key: garden.id,
            text: garden.name,
            value: garden.name,
          }));
          store.dispatch(receivedGardenList(gardenList));
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
