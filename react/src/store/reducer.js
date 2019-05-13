const initialState = {
  username: '',
  password: '',
  firstName: '',
  lastName: '',
  email: '',
  phoneNumber: '',
  address1: '',
  address2: '',
  zipcode: '',
  loading: false,
  loginMessage: 'Message personnalisé',
  loggedIn: true,
  user: {},
  gardenList: [
    { key: 'm', text: 'garden1', value: 'garden1' },
    { key: 'n', text: 'garden2', value: 'garden2' },
  ],
};


export const LOG_USER = 'LOG_USER';
const CHANGE_LOGIN_MESSAGE = 'CHANGE_LOGIN_MESSAGE';
const USER_LOGGED = 'USER_LOGGED';
const USER_LOGOUT = 'USER_LOGOUT';
export const INPUT_CHANGE = 'INPUT_CHANGE';
export const MODIFY_USER_INFOS = 'MODIFY_USER_INFOS';
export const REGISTER_USER = 'REGISTER_USER';

const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case USER_LOGGED:
      return {
        ...state,
        loading: false,
        loginMessage: '',
        loggedIn: true,
        user: { ...action.user },
        repos: [...action.repos],
      };
    case USER_LOGOUT:
      return {
        ...state,
        loggedIn: false,
      };
    case LOG_USER:
      return {
        ...state,
        loading: true,
        loginMessage: 'Logging user',
      };
    case CHANGE_LOGIN_MESSAGE:
      return {
        ...state,
        loginMessage: action.text,
      };
    case INPUT_CHANGE:
      return {
        ...state,
        [action.name]: action.value,
      };
    case REGISTER_USER:
      return {
        ...state,
        loading: true,
      };
    case MODIFY_USER_INFOS:
      return {
        ...state,
        loading: true,
      };
    default:
      return state;
  }
};


export const logUser = () => ({
  type: LOG_USER,
});

export const changeLoginMessage = text => ({
  type: CHANGE_LOGIN_MESSAGE,
  text,
});

export const userLogged = (user, repos) => ({
  type: USER_LOGGED,
  user,
  repos,
});

export const userLogout = () => ({
  type: USER_LOGOUT,
});

export const inputChange = (name, value) => ({
  type: INPUT_CHANGE,
  name,
  value,
});

export const ModifyUserInfos = () => ({
  type: MODIFY_USER_INFOS,
});

export const registerUser = () => ({
  type: REGISTER_USER,
});

export default reducer;
