import { connect } from 'react-redux';
import PostDetail from '../components/PostDetail';

import { fetchQuestionDetail } from '../store/reducer';

const mapStateToProps = '';

const mapDispatchToProps = dispatch => ({
  fetchQuestionDetail: (postId) => {
    dispatch(fetchQuestionDetail(postId));
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PostDetail);
