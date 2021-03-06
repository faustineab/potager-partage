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
  gardenPlots: [],
  askingQuestion: false,
  questionTitle: '',
  questionToAsk: '',
  questionList: [],
  questionToDelete: '',
  questionTags: [],
  tags: [],
  questionDetail: {},
  openQuestionId: '',
  answer: '',
  answerToDelete: '',
  openPlotId: '',
  plotOpened: false,
  plotData: {},
  addingVegetable: {},
  isUserPlot: false,
  vegetablesList: {},
  newVegetableId: '',
  removeVegetableId: '',
};


/**
 * Types
 */

export const CREATE_GARDEN = 'CREATE_GARDEN';
export const JOIN_GARDEN = 'JOIN_GARDEN';
export const LOG_USER = 'LOG_USER';
export const FETCH_USER_INFOS = 'FETCH_USER_INFOS';
export const FETCH_VEGETABLES_LIST = 'FETCH_VEGETABLES_LIST';
export const SAVE_VEGETABLES_LIST = 'SAVE_VEGETABLES_LIST';
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
const VEGETABLE_TO_ADD = 'VEGETABLE_TO_ADD';
export const BOOK_PLOT = 'BOOK_PLOT';
export const UNLINK_PLOT = 'UNLINK_PLOT';
export const PLOT_BOOKED = 'PLOT_BOOKED';
export const SAVE_QUESTION_ID = 'SAVE_QUESTION_ID';
export const FETCH_QUESTION_DETAIL = 'FETCH_QUESTION_DETAIL';
export const NEW_VEGETABLE = 'NEW_VEGETABLE';
export const REMOVE_VEGETABLE = 'REMOVE_VEGETABLE';
export const SEND_ANSWER = 'SEND_ANSWER';
const ANSWER_SENT = 'ANSWER_SENT';
export const DELETE_ANSWER = 'DELETE_ANSWER';
export const QUESTION_DETAIL_FETCHED = 'QUESTION_DETAIL_FETCHED';


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
    case FETCH_VEGETABLES_LIST:
      return {
        ...state,
      };
    case SAVE_VEGETABLES_LIST:
      return {
        ...state,
        vegetablesList: action.vegetablesList,
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
    case SAVE_QUESTION_ID:
      return {
        ...state,
        openQuestionId: action.id,
      };
    case FETCH_QUESTION_DETAIL:
      return {
        ...state,
      };
    case QUESTION_DETAIL_FETCHED:
      return {
        ...state,
        questionDetail: action.questionDetail,
      };
    case SEND_ANSWER:
      return {
        ...state,
      };
    case ANSWER_SENT:
      return {
        ...state,
        answer: '',
      };
    case DELETE_ANSWER:
      return {
        ...state,
        answerToDelete: action.answerId,
      };
    case OPEN_PLOT:
      return {
        ...state,
        openPlotId: action.id,
        plotOpened: true,
      };
    case PLOT_DATA_FETCHED:
      return {
        ...state,
        plotData: { ...action.plotData },
        isUserPlot: action.isUserPlot,
      };
    case VEGETABLE_TO_ADD:
      return {
        ...state,
        addingVegetable: action.vegetableName,
      };
    case BOOK_PLOT:
      return {
        ...state,
      };
    case PLOT_BOOKED:
      return {
        ...state,
      };
    case UNLINK_PLOT:
      return {
        ...state,
      };
    case USER_LOGOUT:
      return {
        ...initialState,
      };
    case NEW_VEGETABLE:
      return {
        ...state,
        newVegetableId: action.newVegetableId,
        addingVegetable: '',
      };
    case REMOVE_VEGETABLE:
      return {
        ...state,
        removeVegetableId: action.removeVegetableId,
      };
    default:
      return state;
  }
};


/**
 * Action Creators
 */

export const removeVegetable = removeVegetableId => ({
  type: REMOVE_VEGETABLE,
  removeVegetableId,
});

export const newVegetable = newVegetableId => ({
  type: NEW_VEGETABLE,
  newVegetableId,
});

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

export const fetchVegetablesList = () => ({
  type: FETCH_VEGETABLES_LIST,
});

export const saveVegetablesList = vegetablesList => ({
  type: SAVE_VEGETABLES_LIST,
  vegetablesList,
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

export const forumQuestionsFetched = (tagList) => {
  //console.log('tagList reducer', tagList);
  return ({
    type: FORUM_QUESTIONS_FETCHED,
    tagList,
  });
};


export const deleteCard = cardId => ({
  type: DELETE_CARD,
  cardId,
});

export const saveQuestionId = id => ({
  type: SAVE_QUESTION_ID,
  id,
});

export const fetchQuestionDetail = () => ({
  type: FETCH_QUESTION_DETAIL,
});


export const sendAnswer = () => ({
  type: SEND_ANSWER,
});

export const answerSent = () => ({
  type: ANSWER_SENT,
});

export const deleteAnswer = answerId => ({
  type: DELETE_ANSWER,
  answerId,
});

export const questionDetailFetched = questionDetail => ({
  type: QUESTION_DETAIL_FETCHED,
  questionDetail,
});

export const openPlot = id => ({
  type: OPEN_PLOT,
  id,
});

export const plotDataFetched = (plotData, isUserPlot) => ({
  type: PLOT_DATA_FETCHED,
  plotData,
  isUserPlot,
});

export const bookPlot = () => ({
  type: BOOK_PLOT,
});

export const unlinkPlot = () => ({
  type: UNLINK_PLOT,
});

export const plotBooked = () => ({
  type: PLOT_BOOKED,
});

export const vegetableToAdd = vegetableName => ({
  type: VEGETABLE_TO_ADD,
  vegetableName,
})

/**
 * Selectors
 */


export default reducer;
