import axios from 'axios';

const ajaxMiddleware = store => next => (action) => {
  switch (action.type) {
    default:
      next(action);
      break;
  }
};

export default ajaxMiddleware;
