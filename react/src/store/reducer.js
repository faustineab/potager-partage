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
  loggedIn: false,
  user: {},
  gardenList: [
    { key: 'm', text: 'garden1', value: 'garden1' },
    { key: 'n', text: 'garden2', value: 'garden2' },
  ],
  gardenName: '',
  gardenAddress: '',
  gardenZipcode: '',
  gardenAddressSpecificities: '',
  gardenCity: '',
  gardenNbPlotsRow: 1,
  gardenPlotsColumn: 1,
  gardenSize: '',
  askingQuestion: false,
  questionToAsk: '',
  questionTag: '',
  tags: [
    { key: 'm', text: 'Fruits & Légumes', value: 'Fruits & Légumes' },
    { key: 'n', text: 'Autour du jardin', value: 'Autour du jardin' },
    { key: 'o', text: 'Trucs & Astuces', value: 'Trucs & Astuces' },
  ],
};


export const LOG_USER = 'LOG_USER';
const CHANGE_LOGIN_MESSAGE = 'CHANGE_LOGIN_MESSAGE';
const USER_LOGGED = 'USER_LOGGED';
const USER_LOGOUT = 'USER_LOGOUT';
export const INPUT_CHANGE = 'INPUT_CHANGE';
export const MODIFY_USER_INFOS = 'MODIFY_USER_INFOS';
export const REGISTER_USER = 'REGISTER_USER';
export const USER_ASKING_QUESTION = 'USER_ASKING_QUESTION';
export const QUESTION_ASKED = 'QUESTION_ASKED';


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
    case USER_ASKING_QUESTION:
      return {
        ...state,
        askingQuestion: !state.askingQuestion,
      };
    case QUESTION_ASKED:
      return {
        ...state,
        askingQuestion: false,
        questionToAsk: action.question,
        questionTag: action.tag,
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

export const userAskingQuestion = () => ({
  type: USER_ASKING_QUESTION,
});

export const questionAsked = (question, tag) => ({
  type: QUESTION_ASKED,
  question,
  tag,
});

export default reducer;
