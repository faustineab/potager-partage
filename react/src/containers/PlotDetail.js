import { connect } from 'react-redux';


import PlotDetail from 'src/components/Potager/PlotDetail';

import { bookPlot } from '../store/reducer';


const mapStateToProps = state => ({
  plotStatus: state.plotData.status,
  isUserPlot: state.isUserPlot,
  vegetablesList: state.vegetablesList,
  plotData: state.plotData,
  // plotStatus: 'actif',
  // isUserPlot: false,
  plotId: state.openPlotId,
});

const mapDispatchToProps = dispatch => ({
  bookPlot: () => {
    dispatch(bookPlot());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PlotDetail);
