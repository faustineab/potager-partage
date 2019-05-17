import axios from 'axios';

import {
  FETCH_GARDENLIST,
  CREATE_GARDEN,
  JOIN_GARDEN,
  LOG_USER,
  FETCH_USER_INFOS,
  receivedGardenList,
  fetchUserInfos,
  fetchGardenInfos,
  FETCH_GARDEN_INFOS,
  saveUserInfos,
  userLogged,
  FETCH_FORUM_QUESTIONS,
  fetchForumTags,
  FETCH_FORUM_TAGS,
  forumQuestionsFetched,
  MODIFY_USER_INFOS,
  SUBMIT_QUESTION,
  questionAsked,
  userLogout,
  DELETE_CARD,
} from 'src/store/reducer';


const ajaxMiddleware = store => next => (action) => {
  switch (action.type) {
    case FETCH_GARDENLIST:
      next(action);
      axios.get('http://localhost/apo/potager-partage/symfo/public/register/user')
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
          store.dispatch()
        });
      break;

    case CREATE_GARDEN:
      next(action);
      axios.post('http://localhost/apo/potager-partage/symfo/public/register/admin', {
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
        })
        .catch((error) => {
          console.log(error);
        });
      break;

    case JOIN_GARDEN:
      next(action);
      axios.post('http://localhost/apo/potager-partage/symfo/public/register/user', {
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
          window.location.href = '/subscribe';
        });
      break;

    case LOG_USER:
      next(action);
      axios.post('http://localhost/apo/potager-partage/symfo/public/login', {
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
      axios.get('http://localhost/apo/potager-partage/symfo/public/api/login', {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          const gardenList = response.data.gardens;
          const user = response.data;
          console.log('response.data', response.data);
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
      axios.get(`http://localhost/apo/potager-partage/symfo/public/api/garden/${store.getState().gardenId}`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
      })
        .then((response) => {
          console.log(response);
          const gardenName = response.data.name;
          const gardenAddress = response.data.address;
          const gardenZipcode = response.data.zipcode;
          const gardenAddressSpecificities = response.data.addressSpecificities;
          const gardenCity = response.data.city;
          const gardenNbPlotsRow = response.data.number_plots_row;
          const gardenNbPlotsColumn = response.number_plots_column;
          const gardenSize = response.data.meters;

          store.dispatch(userLogged(
            gardenName,
            gardenAddress,
            gardenZipcode,
            gardenAddressSpecificities,
            gardenCity,
            gardenNbPlotsColumn,
            gardenNbPlotsRow,
            gardenSize,
          ));
        })
        .catch((error) => {
          console.log(error);
          store.dispatch(userLogout());
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
          console.log(response.data);
          const questionList = response.data;
          const formattedQuestionList = questionList.map(list => ({
            id: list.id,
            text: list.text,
            title: list.title,
            user: list.user,
            creationDate: list.createdAt.substring(0, 10),
          }));
          console.log(formattedQuestionList);
          store.dispatch(fetchForumTags(formattedQuestionList));
        })
        .catch((error) => {
          console.log(error);
        });
      break;
    default:
      next(action);
      break;

    case FETCH_FORUM_TAGS:
      next(action);
      axios.get(`http://localhost/apo/potager-partage/symfo/public/api/garden/${store.getState().gardenId}/forum/tag`, {
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
      axios.post(`http://localhost/apo/potager-partage/symfo/public/api/garden/${store.getState().gardenId}/forum/question/new`, {
        title: store.getState().questionTitle,
        text: store.getState().questionToAsk,
        tag: store.getState().questionTags,
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
    // case DELETE_CARD:
    //   next(action);
    //   axios.delete(``)
    //   break;
    case MODIFY_USER_INFOS:
      next(action);

      console.log(store.getState().user.id);
      axios.put(`http://localhost/apo/potager-partage/symfo/public/api/user/${store.getState().user.id}/edit`, {
        headers: {
          Authorization: `Bearer ${store.getState().token}`,
        },
        name: `${store.getState().firstName} ${store.getState().lastName}`,
        email: store.getState().email,
        phone: store.getState().phoneNumber,
        address: store.getState().address,
        gardenZipCode: store.getState().gardenZipcode,
      })
        .then((response) => {
          console.log(response);
        })
        .catch((error) => {
          console.log(error);
        });
      break;
  }
};

export default ajaxMiddleware;
