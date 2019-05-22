import { connect } from 'react-redux';
import Potager from '../components/Potager';

import { openPlot } from '../store/reducer';

const mapStateToProps = state => ({
  plots: state.gardenPlots,
  length: state.gardenNbPlotsRow,
  width: state.gardenNbPlotsColumn,
  plotOpened: state.plotOpened,
});

const mapDispatchToProps = dispatch => ({
  openPlot: (id) => {
    dispatch(openPlot(id));
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Potager);
