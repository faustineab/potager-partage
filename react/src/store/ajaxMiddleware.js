import axios from 'axios';

import {
  FETCH_GARDENLIST,
  CREATE_GARDEN,
  JOIN_GARDEN,
  LOG_USER,
  logUser,
  FETCH_USER_INFOS,
  FETCH_VEGETABLES_LIST,
  fetchVegetablesList,
  saveVegetablesList,
  receivedGardenList,
  fetchUserInfos,
  fetchGardenInfos,
  FETCH_GARDEN_INFOS,
  saveUserInfos,
  userLogged,
  fetchForumQuestions,
  FETCH_FORUM_QUESTIONS,
  fetchForumTags,
  FETCH_FORUM_TAGS,
  forumQuestionsFetched,
  MODIFY_USER_INFOS,
  SUBMIT_QUESTION,
  questionAsked,
  QUESTION_ASKED,
  FETCH_QUESTION_DETAIL,
  questionDetailFetched,
  SEND_ANSWER,
  answerSent,
  DELETE_ANSWER,
  userLogout,
  DELETE_CARD,
  OPEN_PLOT,
  plotDataFetched,
  BOOK_PLOT,
  plotBooked,
  UNLINK_PLOT,
  NEW_VEGETABLE,
  REMOVE_VEGETABLE,
  openPlot,
  fetchQuestionDetail } from './reducer';


const baseURL = 'http://217.70.191.127';
// http://localhost/apo/potager-partage/symfo/public
// http://217.70.191.127

const ajaxMiddleware = store => next => (action) => {
  switch (action.type) {
    case FETCH_GARDENLIST:
      next(action);
      axios.get(`${baseURL}/register/user`)
        .then((response) => {
          // console.log('response data', response.data);
          const gardenList = response.data.map(garden => ({
            key: garden.id,
            text: garden.name,
            value: garden.id,
          }));
          store.dispatch(receivedGardenList(gardenList));
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case CREATE_GARDEN:
      next(action);
      axios.post(`${baseURL}/register/admin`, {
        name: `${store.getState().firstName} ${store.getState().lastName}`,
        email: store.getState().email,
        password: store.getState().password,
        phone: store.getState().phoneNumber,
        address: store.getState().address,
        gardenName: store.getState().gardenName,
        gardenAddress: store.getState().gardenAddress,
        gardenSpecificities: store.getState().gardenAddressSpecificities,
        gardenZipCode: store.getState().gardenZipcode,
        gardenCity: store.getState().gardenCity,
        gardenMeters: store.getState().gardenSize,
        gardenPlots_Row: store.getState().gardenNbPlotsRow,
        gardenPlots_Column: store.getState().gardenNbPlotsColumn,
      })
        .then((response) => {
          console.log(response);
          store.dispatch(logUser());
        })
        .catch((error) => {
          console.log(error);
          window.location.href = '/subscribe';
        });
      break;

    case JOIN_GARDEN:
      next(action);
      axios.post(`${baseURL}/register/user`, {
        name: `${store.getState().firstName} ${store.getState().lastName}`,
        gardenId: store.getState().gardenId,
        email: store.getState().email,
        password: store.getState().password,
        phone: store.getState().phoneNumber,
        address: store.getState().address,
      })
        .then((response) => {
          console.log(response);

          // window.location.href = '/';
        })
        .catch((error) => {
          console.log(error);
        //   window.location.href = '/subscribe';
        });
      break;

    case LOG_USER:
      next(action);
      axios.post(`${baseURL}/login`, {
        username: store.getState().email,
        password: store.getState().password,
      })
        .then((response) => {
          console.log(response.data.token);
          store.dispatch(fetchUserInfos(response.data.token));
        })
        .catch((error) => {
          console.log(error);
          store.dispatch(userLogout());
        });
      break;

    case FETCH_USER_INFOS:
      next(action);
      axios.get(`${baseURL}/api/login`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          const gardenList = response.data.gardens;
          const user = response.data;
          console.log('response.data.user', response.data);
          console.log(gardenList);
          if (gardenList.length > 1) {
            store.dispatch(saveUserInfos(user));
          }
          else {
            const gardenId = gardenList[0].id;
            store.dispatch(fetchGardenInfos(user, gardenId));
          }
        })
        .catch((error) => {
          console.log(error);
          store.dispatch(userLogout());
        });
      break;

    case FETCH_GARDEN_INFOS:
      next(action);
      axios.get(`${baseURL}/api/garden/${store.getState().gardenId}`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log('response.data.garden', response.data);
          const gardenName = response.data.name;
          const gardenAddress = response.data.address;
          const gardenZipcode = response.data.zipcode;
          const gardenAddressSpecificities = response.data.addressSpecificities;
          const gardenCity = response.data.city;
          const gardenNbPlotsRow = response.data.number_plots_row;
          const gardenNbPlotsColumn = response.data.number_plots_column;
          const gardenSize = response.data.meters;
          const gardenPlots = response.data.plots;

          store.dispatch(userLogged(
            gardenName,
            gardenAddress,
            gardenZipcode,
            gardenAddressSpecificities,
            gardenCity,
            gardenNbPlotsColumn,
            gardenNbPlotsRow,
            gardenSize,
            gardenPlots,
          ));
          store.dispatch(fetchVegetablesList());
        })
        .catch((error) => {
          console.log(error);
          store.dispatch(userLogout());
        });
      break;

    case FETCH_VEGETABLES_LIST:
      next(action);
      axios.get(`${baseURL}/api/vegetable/`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response.data);
          const vegetablesList = response.data;
          const formattedVegetablesList = vegetablesList.map(vegetable => ({
            key: vegetable.id,
            text: vegetable.name,
            value: vegetable.name,
          }));
          console.log(formattedVegetablesList);
          store.dispatch(saveVegetablesList(formattedVegetablesList));
        })
        .catch((error) => {
          console.log('forum question error', error);
        });
      break;

    case FETCH_FORUM_QUESTIONS:
      next(action);
      axios.get(`http://localhost/apo/potager-partage/symfo/public/api/garden/${store.getState().gardenId}/forum/question`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log('forum questions', response.data);
          const questionList = response.data;
          const formattedQuestionList = questionList.map(list => ({
            id: list.id,
            text: list.text,
            title: list.title,
            user: list.user,
            tags: list.tags,
            creationDate: list.createdAt.substring(0, 10),
          }));
          console.log(formattedQuestionList);
          store.dispatch(fetchForumTags(formattedQuestionList));
        })
        .catch((error) => {
          console.log('forum question error', error);
        });
      break;

    case FETCH_FORUM_TAGS:
      next(action);
      axios.get(`${baseURL}/api/garden/${store.getState().gardenId}/forum/tag`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log('tags', response.data);
          const tagList = response.data;
          const formattedTagList = tagList.map(tag => ({
            key: tag.id,
            text: tag.name,
            value: tag.name,
          }));
          console.log(formattedTagList);
          store.dispatch(forumQuestionsFetched(formattedTagList));
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case SUBMIT_QUESTION:
      next(action);
      axios.post(`${baseURL}/api/garden/${store.getState().gardenId}/forum/question/new`, {
        title: store.getState().questionTitle,
        text: store.getState().questionToAsk,
        tags: store.getState().questionTags,
      },
      {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response.data);
          store.dispatch(questionAsked());
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    case QUESTION_ASKED:
      next(action);
      store.dispatch(fetchForumQuestions());
      break;

    case DELETE_CARD:
      next(action);
      axios.delete(`${baseURL}/api/garden/${store.getState().gardenId}/forum/question/${store.getState().questionToDelete}`,
        {
          headers: {
            Authorization: `Bearer ${store.getState().token}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          store.dispatch(fetchForumQuestions());
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case FETCH_QUESTION_DETAIL:
      next(action);
      axios.get(`http://localhost/apo/potager-partage/symfo/public/api/garden/${store.getState().gardenId}/forum/question/${store.getState().openQuestionId}`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response.data);
          const questionDetail = response.data;
          store.dispatch(questionDetailFetched(questionDetail));
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case SEND_ANSWER:
      if (store.getState().answer !== '') {
        next(action);
        axios.post(`${baseURL}/api/garden/${store.getState().gardenId}/forum/question/${store.getState().openQuestionId}/answer/new`, {
          text: store.getState().answer,
        },
        {
          headers: {
            Authorization: `Bearer ${store.getState().token}`,
          },
        })
          .then((response) => {
            console.log(response.data);
            store.dispatch(fetchQuestionDetail());
            store.dispatch(answerSent());
          })
          .catch((error) => {
            console.log(error);
          });
      }
      break;

    case DELETE_ANSWER:
      next(action);
      axios.delete(`http://localhost/apo/potager-partage/symfo/public/api/garden/${store.getState().gardenId}/forum/question/${store.getState().openQuestionId}/answer/${store.getState().answerToDelete}`,
        {
          headers: {
            Authorization: `Bearer ${store.getState().token}`,
          },
        })
        .then((response) => {
          console.log(response.data);
          store.dispatch(fetchQuestionDetail());
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case OPEN_PLOT:
      next(action);
      axios.get(`${baseURL}/api/garden/${store.getState().gardenId}/plots/${store.getState().openPlotId}`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log('response data = ', response.data);
          console.log('status actif', response.data.status === 'actif');

          const isUserPlot = (response.data.status === 'actif') ? (response.data.user.id === store.getState().user.id) : false;

          console.log('isUserPlot', isUserPlot);

          store.dispatch(plotDataFetched(response.data, isUserPlot));
        })
        .catch((error) => {
          console.log('ERROR OPEN PLOT', error);
        });
      break;
    case BOOK_PLOT:
      next(action);
      axios.put(`${baseURL}/api/garden/${store.getState().gardenId}/plots/${store.getState().openPlotId}/edit`, {}, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response);
          store.dispatch(fetchGardenInfos(store.getState().user, store.getState().gardenId));
          store.dispatch(openPlot(store.getState().openPlotId));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    case UNLINK_PLOT:
      next(action);
      axios.put(`${baseURL}/api/garden/${store.getState().gardenId}/plots/${store.getState().openPlotId}/remove`, {}, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response);
          store.dispatch(fetchGardenInfos(store.getState().user, store.getState().gardenId));
          store.dispatch(openPlot(store.getState().openPlotId));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    case MODIFY_USER_INFOS:
      next(action);
      axios.put(`${baseURL}/api/user/${store.getState().user.id}/edit`, {
        name: `${store.getState().firstName} ${store.getState().lastName}`,
        email: store.getState().email,
        phone: store.getState().phoneNumber,
        address: store.getState().address,
      },
      {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    case NEW_VEGETABLE:
      next(action);
      axios.post(`${baseURL}/api/garden/${store.getState().gardenId}/plots/${store.getState().openPlotId}/plantation/new`, {
        vegetable: store.getState().newVegetableId,
        seedling_date: '2019-05-21',
      },
      {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response);
          store.dispatch(openPlot(store.getState().openPlotId));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    case REMOVE_VEGETABLE:
      next(action);
      axios.delete(`${baseURL}/api/garden/${store.getState().gardenId}/plantation/${store.getState().removeVegetableId}`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response);
          store.dispatch(openPlot(store.getState().openPlotId));
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
