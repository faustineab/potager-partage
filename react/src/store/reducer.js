/**
 * Initial State
 */

//  test

const initialState = {
  password: '',
  firstName: '',
  lastName: '',
  email: '',
  phoneNumber: '',
  address: '',
  loading: false,
  loginMessage: '',
  loggedIn: false,
  loginStatus: 'logout',
  token: '',
  user: {},
  gardenList: [],
  gardenId: '',
  gardenName: '',
  gardenAddress: '',
  gardenZipcode: '',
  gardenAddressSpecificities: '',
  gardenCity: '',
  gardenNbPlotsRow: 1,
  gardenNbPlotsColumn: 1,
  gardenSize: '',
  askingQuestion: false,
  questionToAsk: '',
  questionTags: [],
  tags: [
    { key: 'm', text: 'Fruits & Légumes', value: 'Fruits & Légumes' },
    { key: 'n', text: 'Autour du jardin', value: 'Autour du jardin' },
    { key: 'o', text: 'Trucs & Astuces', value: 'Trucs & Astuces' },
  ],
};


/**
 * Types
 */

export const CREATE_GARDEN = 'CREATE_GARDEN';
export const JOIN_GARDEN = 'JOIN_GARDEN';
export const LOG_USER = 'LOG_USER';
export const FETCH_USER_INFOS = 'FETCH_USER_INFOS';
export const SAVE_USER_INFOS = 'SAVE_USER_INFOS';
export const CHOOSE_GARDEN = 'CHOOSE_GARDEN';
export const FETCH_GARDEN_INFOS = 'FETCH_GARDEN_INFOS';
const USER_LOGGED = 'USER_LOGGED';
const USER_LOGOUT = 'USER_LOGOUT';
export const INPUT_CHANGE = 'INPUT_CHANGE';
export const MODIFY_USER_INFOS = 'MODIFY_USER_INFOS';
export const FETCH_GARDENLIST = 'FETCH_GARDENLIST';
export const USER_ASKING_QUESTION = 'USER_ASKING_QUESTION';
export const ADD_TAG_TO_QUESTION = 'ADD_TAG_TO_QUESTION';
export const REMOVE_QUESTION_TAG = 'REMOVE_QUESTION_TAG';
export const QUESTION_ASKED = 'QUESTION_ASKED';
export const RECEIVED_GARDENLIST = 'RECEIVED_GARDENLIST';


/**
 * Reducer
 */

const reducer = (state = initialState, action = {}) => {
  switch (action.type) {
    case CREATE_GARDEN:
      return {
        ...state,
      };
    case JOIN_GARDEN:
      return {
        ...state,
      };
    case LOG_USER:
      return {
        ...state,
        loading: true,
        loginMessage: 'Vérification des credentials',
      };
    case FETCH_USER_INFOS:
      return {
        ...state,
        token: action.token,
        loginMessage: 'Récupération des données utilisateur',
      };
    case SAVE_USER_INFOS:
      return {
        ...state,
        user: { ...action.user },
        loginStatus: 'chooseGarden',
        loading: false,
      };
    case CHOOSE_GARDEN:
      return {
        ...state,
      };
    case FETCH_GARDEN_INFOS:
      return {
        ...state,
        user: { ...action.user },
        gardenId: action.gardenId,
        loginMessage: 'Récupération des données du jardin',
      };
    case USER_LOGGED:
      return {
        ...state,
        loading: false,
        loginMessage: '',
        loggedIn: true,
        loginStatus: 'loggedIn',
        gardenName: action.gardenName,
        gardenAddress: action.gardenAddress,
        gardenAddressSpecificities: action.gardenAddressSpecificities,
        gardenCity: action.gardenCity,
        gardenZipcode: action.gardenZipcode,
        gardenNbPlotsColumn: action.gardenNbPlotsColumn,
        gardenNbPlotsRow: action.gardenNbPlotsRow,
        gardenSize: action.gardenSize,
      };
    case USER_LOGOUT:
      return {
        ...state,
        loggedIn: false,
      };
    case INPUT_CHANGE:
      return {
        ...state,
        [action.name]: action.value,
      };
    case FETCH_GARDENLIST:
      return {
        ...state,
        loading: true,
      };
    case RECEIVED_GARDENLIST:
      return {
        ...state,
        loading: false,
        gardenList: [...action.gardenList],
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
    case ADD_TAG_TO_QUESTION:
      return {
        ...state,
        questionTags: [...state.questionTags, action.tag],
      };
    case REMOVE_QUESTION_TAG:
      return {
        ...state,
        questionTags: [...action.tagList],
      };
    case QUESTION_ASKED:
      return {
        ...state,
        askingQuestion: false,
        questionToAsk: '',
        questionTags: [],
      };
    default:
      return state;
  }
};


/**
 * Action Creators
 */
export const joinGarden = () => ({
  type: JOIN_GARDEN,
});

export const createGarden = () => ({
  type: CREATE_GARDEN,
});

export const logUser = () => ({
  type: LOG_USER,
});

export const fetchUserInfos = token => ({
  type: FETCH_USER_INFOS,
  token,
});

export const saveUserInfos = user => ({
  type: SAVE_USER_INFOS,
  user,
})

export const chooseGarden = () => ({
  type: CHOOSE_GARDEN,

})

export const fetchGardenInfos = (user, gardenId) => ({
  type: FETCH_GARDEN_INFOS,
  user,
  gardenId,
});

export const userLogged = (
  user,
  gardenName,
  gardenAddress,
  gardenAddressSpecificities,
  gardenCity,
  gardenZipcode,
  gardenNbPlotsColumn,
  gardenNbPlotsRow,
  gardenSize,
) => ({
  type: USER_LOGGED,
  user,
  gardenName,
  gardenAddress,
  gardenAddressSpecificities,
  gardenCity,
  gardenZipcode,
  gardenNbPlotsColumn,
  gardenNbPlotsRow,
  gardenSize,
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

export const fetchGardenlist = () => ({
  type: FETCH_GARDENLIST,
});

export const receivedGardenList = gardenList => ({
  type: RECEIVED_GARDENLIST,
  gardenList,
});

export const userAskingQuestion = () => ({
  type: USER_ASKING_QUESTION,
});

export const addTagToQuestion = tag => ({
  type: ADD_TAG_TO_QUESTION,
  tag,
});

export const removeQuestionTag = tagList => ({
  type: REMOVE_QUESTION_TAG,
  tagList,
});

export const questionAsked = (question, tag) => ({
  type: QUESTION_ASKED,
  question,
  tag,
});


/**
 * Selectors
 */


export default reducer;
