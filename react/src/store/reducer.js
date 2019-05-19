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
  gardenId: '177',
  gardenName: '',
  gardenAddress: '',
  gardenZipcode: '',
  gardenAddressSpecificities: '',
  gardenCity: '',
  gardenNbPlotsRow: 1,
  gardenNbPlotsColumn: 1,
  gardenSize: '',
  gardenPlots: [],
  askingQuestion: false,
  questionTitle: '',
  questionToAsk: '',
  questionList: [],
  questionToDelete: '',
  questionTags: [],
  tags: [
    { key: 'm', text: 'Fruits & Légumes', value: 'Fruits & Légumes' },
    { key: 'n', text: 'Autour du jardin', value: 'Autour du jardin' },
    { key: 'o', text: 'Trucs & Astuces', value: 'Trucs & Astuces' },
  ],
  openPlotId: '',
  plotData: {},
  isUserPlot: false,
};


/**
 * Types
 */

export const CREATE_GARDEN = 'CREATE_GARDEN';
export const JOIN_GARDEN = 'JOIN_GARDEN';
export const LOG_USER = 'LOG_USER';
export const FETCH_USER_INFOS = 'FETCH_USER_INFOS';
export const SAVE_USER_INFOS = 'SAVE_USER_INFOS';
export const FETCH_GARDEN_INFOS = 'FETCH_GARDEN_INFOS';
const USER_LOGGED = 'USER_LOGGED';
const USER_LOGOUT = 'USER_LOGOUT';
export const INPUT_CHANGE = 'INPUT_CHANGE';
export const MODIFY_USER_INFOS = 'MODIFY_USER_INFOS';
export const FETCH_GARDENLIST = 'FETCH_GARDENLIST';
export const USER_ASKING_QUESTION = 'USER_ASKING_QUESTION';
export const ADD_TAG_TO_QUESTION = 'ADD_TAG_TO_QUESTION';
export const REMOVE_QUESTION_TAG = 'REMOVE_QUESTION_TAG';
export const SUBMIT_QUESTION = 'SUBMIT_QUESTION';
export const QUESTION_ASKED = 'QUESTION_ASKED';
export const RECEIVED_GARDENLIST = 'RECEIVED_GARDENLIST';
export const FETCH_FORUM_QUESTIONS = 'FETCH_FORUM_QUESTIONS';
export const FETCH_FORUM_TAGS = 'FETCH_FORUM_TAGS';
export const FORUM_QUESTIONS_FETCHED = 'FORUM_QUESTIONS_FETCHED';
export const DELETE_CARD = 'DELETE_CARD';
export const OPEN_PLOT = 'OPEN_PLOT';
export const PLOT_DATA_FETCHED = 'PLOT_DATA_FETCHED';
export const BOOK_PLOT = 'BOOK_PLOT';
export const PLOT_BOOKED = 'PLOT_BOOKED';

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
        errorMessage: '',
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
        gardenPlots: [...action.gardenPlots],
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
        questionTitle: '',
        questionToAsk: '',
        questionTags: [],
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
    case SUBMIT_QUESTION:
      return {
        ...state,
      };
    case QUESTION_ASKED:
      return {
        ...state,
        askingQuestion: false,
        questionTitle: '',
        questionToAsk: '',
        questionTags: [],
        errorMessage: '',
      };
    case FETCH_FORUM_QUESTIONS:
      return {
        ...state,
      };
    case FETCH_FORUM_TAGS:
      return {
        ...state,
        questionList: [...action.questionList],
      };
    case FORUM_QUESTIONS_FETCHED:
      return {
        ...state,
        tags: [...action.tagList],
      };
    case DELETE_CARD:
      return {
        ...state,
        questionToDelete: action.cardId,
      };
    case OPEN_PLOT:
      return {
        ...state,
        openPlotId: action.id,
      };
    case PLOT_DATA_FETCHED:
      return {
        ...state,
        plotData: { ...action.plotData },
      };
    case BOOK_PLOT:
      return {
        ...state,
      };
    case PLOT_BOOKED:
      return {
        ...state,
        isUserPlot: true,
      };
    case USER_LOGOUT:
      return {
        ...initialState,
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
});

export const fetchGardenInfos = (user, gardenId) => ({
  type: FETCH_GARDEN_INFOS,
  user,
  gardenId,
});

export const userLogged = (
  gardenName,
  gardenAddress,
  gardenZipcode,
  gardenAddressSpecificities,
  gardenCity,
  gardenNbPlotsColumn,
  gardenNbPlotsRow,
  gardenSize,
  gardenPlots,
) => ({
  type: USER_LOGGED,
  gardenName,
  gardenAddress,
  gardenZipcode,
  gardenAddressSpecificities,
  gardenCity,
  gardenNbPlotsColumn,
  gardenNbPlotsRow,
  gardenSize,
  gardenPlots,
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

export const submitQuestion = () => ({
  type: SUBMIT_QUESTION,
});

export const questionAsked = () => ({
  type: QUESTION_ASKED,
});

export const fetchForumQuestions = () => ({
  type: FETCH_FORUM_QUESTIONS,
});

export const fetchForumTags = questionList => ({
  type: FETCH_FORUM_TAGS,
  questionList,
});

export const forumQuestionsFetched = tagList => ({
  type: FORUM_QUESTIONS_FETCHED,
  tagList,
});

export const deleteCard = cardId => ({
  type: DELETE_CARD,
  cardId,
});

export const openPlot = id => ({
  type: OPEN_PLOT,
  id,
});

export const plotDataFetched = plotData => ({
  type: PLOT_DATA_FETCHED,
  plotData,
});

export const bookPlot = () => ({
  type: BOOK_PLOT,
});

export const plotBooked = () => ({
  type: PLOT_BOOKED,
});

/**
 * Selectors
 */


export default reducer;
