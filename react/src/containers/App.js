import { connect } from 'react-redux';

import App from '../components/App';

import { withRouter } from 'react-router';



const mapStateToProps = state => ({

 loggedIn: state.loggedIn,

});



const mapDispatchToProps = {};



export default withRouter(connect(

 mapStateToProps,

 mapDispatchToProps,

)(App));
