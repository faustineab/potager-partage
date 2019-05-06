const initialState = {
  username: '',
  password: '',
  firstName: 'Jean',
  lastName: 'Dupont',
  email: 'DupontJean@gmail.com',
  phoneNumber: '0632482322',
  address1: '1 rue du jardin',
  address2: '',
  zipcode: '',
  loading: false,
  loginMessage: 'Message personnalisé',
  loggedIn: false,
  user: {},
};

const LOGIN_INPUT_CHANGE = 'LOGIN_INPUT_CHANGE';
const PASSWORD_INPUT_CHANGE = 'PASSWORD_INPUT_CHANGE';
export const LOG_USER = 'LOG_USER';
const CHANGE_LOGIN_MESSAGE = 'CHANGE_LOGIN_MESSAGE';
const USER_LOGGED = 'USER_LOGGED';
const USER_LOGOUT = 'USER_LOGOUT';
export const INPUT_CHANGE = 'INPUT_CHANGE';
export const MODIFY_USER_INFOS = 'MODIFY_USER_INFOS';

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
    case LOGIN_INPUT_CHANGE:
      return {
        ...state,
        username: action.username,
      };
    case PASSWORD_INPUT_CHANGE:
      return {
        ...state,
        password: action.password,
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
    case MODIFY_USER_INFOS:
      return {
        ...state,
        loading: true,
      };
    default:
      return state;
  }
};

export const loginInputChange = username => ({
  type: LOGIN_INPUT_CHANGE,
  username,
});

export const passwordInputChange = password => ({
  type: PASSWORD_INPUT_CHANGE,
  password,
});

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

export default reducer;
