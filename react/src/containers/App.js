import { connect } from 'react-redux';

import App from '../components/App';


const mapStateToProps = state => ({
  loggedIn: state.loggedIn,
  loginStatus: state.loginStatus,
});


const mapDispatchToProps = {};


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App);
