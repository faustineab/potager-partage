import { connect } from 'react-redux';
import PostDetail from '../components/PostDetail';

import { fetchQuestionDetail, inputChange } from '../store/reducer';

const mapStateToProps = '';

const mapDispatchToProps = dispatch => ({
  fetchQuestionDetail: (postId) => {
    dispatch(fetchQuestionDetail(postId));
  },
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PostDetail);
