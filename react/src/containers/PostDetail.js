import { connect } from 'react-redux';
import PostDetail from '../components/PostDetail';

import { fetchQuestionDetail, inputChange, sendAnswer, deleteCard, deleteAnswer } from '../store/reducer';

const mapStateToProps = state => ({
  questionDetail: state.questionDetail,
  answers: state.questionDetail.answers,
  questionTags: state.questionDetail.tags,
  author: state.questionDetail.user,
  ongoingAnswer: state.answer,
  questionId: state.openQuestionId,
});

const mapDispatchToProps = dispatch => ({
  fetchQuestionDetail: () => {
    dispatch(fetchQuestionDetail());
  },
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  sendAnswer: () => {
    dispatch(sendAnswer());
  },
  deleteCard: (cardId) => {
    dispatch(deleteCard(cardId));
  },
  deleteAnswer: (answerId) => {
    dispatch(deleteAnswer(answerId));
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PostDetail);
