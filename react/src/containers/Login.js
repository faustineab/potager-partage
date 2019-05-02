import { connect } from 'react-redux';
import Login from '../components/Login';
import { loginInputChange, passwordInputChange, logUser } from '../store/reducer';

const mapStateToProps = state => ({
  username: state.username,
  password: state.password,
  loginMessage: state.loginMessage,
  loading: state.loading,
});

const mapDispatchToProps = dispatch => ({
  usernameChange: (username) => {
    dispatch(loginInputChange(username));
  },
  passwordChange: (password) => {
    dispatch(passwordInputChange(password));
  },
  onFormSubmit: () => {
    dispatch(logUser());
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Login);
