import axios from 'axios';

import {
  MODIFY_USER_INFOS,
} from 'src/store/reducer';

const ajaxMiddleware = store => next => (action) => {
  switch (action.type) {
    case MODIFY_USER_INFOS:
      next(action);

      const firstName = store.getState();
      const lastName = store.getState();
      const password = store.getState();
      const email = store.getState();
      const phone = store.getState().phoneNumber;
      const address = store.getState().address1;
      const address_specificities = store.getState().address2;
      const zipcode = store.getState();

      axios.post(url, {
        name: `${firstName} ${lastName}`,
        password,
        email,
        phone,
        address,
        address_specificities,
        zipcode,
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
