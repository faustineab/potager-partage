import axios from 'axios';

import {
  REGISTER_USER,
} from 'src/store/reducer';

const ajaxMiddleware = store => next => (action) => { 
  switch (action.type) {
    case REGISTER_USER:
      next(action);

      const firstName = store.getState().firstName;
      const lastName = store.getState().lartName;
      const password = store.getState().password;
      const email = store.getState().email;
      const phone = store.getState().phoneNumber;
      const address = store.getState().address1;
      const address_specificities = store.getState().address2;
      const zipcode = store.getState().zipcode;

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
