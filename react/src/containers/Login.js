import { connect } from 'react-redux';
import Login from '../components/Login';
import { inputChange, logUser } from '../store/reducer';

const mapStateToProps = state => ({
  username: state.username,
  password: state.password,
  loginMessage: state.loginMessage,
  loading: state.loading,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },

  onFormSubmit: () => {
    dispatch(logUser());
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Login);
