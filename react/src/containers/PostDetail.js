import { connect } from 'react-redux';
import PostDetail from '../components/PostDetail';

import { fetchQuestionDetail, inputChange, sendAnswer, deleteCard, deleteAnswer } from '../store/reducer';

const mapStateToProps = state => ({
  questionDetail: state.questionDetail,
  answers: state.questionDetail.answers,
});

const mapDispatchToProps = dispatch => ({
  fetchQuestionDetail: (postId) => {
    dispatch(fetchQuestionDetail(postId));
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
