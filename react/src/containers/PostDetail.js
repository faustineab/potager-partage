import { connect } from 'react-redux';
import PostDetail from '../components/PostDetail';

import { fetchQuestionDetail, inputChange, sendAnswer } from '../store/reducer';

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
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PostDetail);
