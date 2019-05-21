import React from 'react';
import PropTypes from 'prop-types';
import { Button, Form } from 'semantic-ui-react';


const PlotDetail = ({
  isUserPlot, plotStatus, plotId, bookPlot, vegetablesList, plotData,
}) => {
  console.log(isUserPlot, plotStatus, plotId);

  const plotVegetables = [];

  if (plotData.isPlantedOns && plotData.isPlantedOns[0]) {
    for (let i = 0; plotData.isPlantedOns[i]; i++) {
      plotVegetables.push(<li>{plotData.isPlantedOns[i].vegetable.name}</li>);
    }
  }

  return (
    <div>
      <h1 id="asideTitle">Détail de la parcelle</h1>
      {(plotStatus === 'inactif') && (
        <div>
          <h2>Cette parcelle n'est pas encore occupée</h2>
          <Button id="button" content="Réserver" onClick={bookPlot} />
        </div>
      )}

      {isUserPlot && (
        <div>
          {console.log('A MOI')}
          <h2>Vous êtes sur votre parcelle</h2>
          <p>Fruits & légumes cultivés</p>
          <ul id="vegetableList">
            {plotVegetables}
          </ul>
          <Form>
            <Form.Input fluid placeholder="Ajouter un fruit ou un légume" />
          </Form>
        </div>
      )}

      {(plotStatus === 'actif' && !isUserPlot) && (
        <div>
          {console.log('la liste du user actuel : ', plotData.isPlantedOns)}
          <h2>Cette parcelle est occupé par {plotData.user.name}.</h2>
          <p>Fruits & légumes cultivés</p>
          <ul id="vegetableList">
            {plotVegetables}
          </ul>
        </div>
      )}
    </div>
  );
};


// {isUserPlot && <p>test isUserPlot</p>}
// <p>{plotStatus}</p>

// Si plotStatus=inactif

// Si plotStatus=actif et isUserPlot = false

// Si plotStatus=actif et isUserPlot = true

PlotDetail.propTypes = {
  isUserPlot: PropTypes.bool.isRequired,
  plotStatus: PropTypes.string.isRequired,
  plotId: PropTypes.string.isRequired,
  bookPlot: PropTypes.func.isRequired,
};

export default PlotDetail;
