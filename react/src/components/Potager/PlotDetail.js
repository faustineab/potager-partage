import React from 'react';
import PropTypes from 'prop-types';
import {
  Form, Button, Icon, Select, Popup,
} from 'semantic-ui-react';

import './index.scss';

const PlotDetail = ({
  isUserPlot, plotStatus, plotId, bookPlot, vegetablesList, plotData, inputChange, submitVegetable, newVegetable, removeVegetable, vegetableToAdd, addingVegetable, unlinkPlot,
}) => {
  const handleTags = (evt) => {
    removeVegetable(evt.currentTarget.id);
  };

  console.log('is planted on', plotData.isPlantedOns);
  const plotVegetableList = plotData.isPlantedOns;

  // const plotVegetables = [];

  // if (plotData.isPlantedOns && plotData.isPlantedOns[0]) {
  //   for (let i = 0; plotData.isPlantedOns[i]; i++) {
  //     plotVegetables.push(
  //       <div key={i} className="vegetable">
  //         <li>{plotData.isPlantedOns[i].vegetable.name}
  //           <div className="tag">
  //             <Icon id={plotData.isPlantedOns[i].id} size="small" onClick={handleTags} name="delete" />
  //           </div>
  //         </li>
  //       </div>,
  //     );
  //   }
  // }
  const handleSubmit = (evt) => {
    evt.preventDefault();

    const vegetablesInPlot = plotVegetableList.map(({ vegetable }) => vegetable.name);

    if (!vegetablesInPlot.includes(addingVegetable)) {
      let addingVegetableId = '';

      for (let i = 0; vegetablesList[i]; i++) {
        if (vegetablesList[i].text === addingVegetable) {
          addingVegetableId = vegetablesList[i].key;
          break;
        }
      }

      submitVegetable(addingVegetableId);
    }
  };

  const handleListChange = (evt) => {
    console.log(evt.currentTarget.outerText);
    vegetableToAdd(evt.currentTarget.outerText);
  };


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
          <h2>Vous êtes sur votre parcelle
            <Popup
              content="libérer la parcelle"
              style={{ FontFamily: 'Fresca, sans-serif', color: '#5e5250' }}
              trigger={<Icon size="small" name="unlink" id="unlink" onClick={unlinkPlot} />}
            />
          </h2>
          <p>Fruits & légumes cultivés</p>
          <ul id="vegetableList">
            {plotVegetableList.map(({ id, vegetable }) => (
              <li>
                {vegetable.name}
                <div className="tag">
                  <Icon id={id} size="small" onClick={handleTags} name="delete" />
                </div>
              </li>
            ))}
          </ul>
          <Form onSubmit={handleSubmit}>
            <Form.Group>
              <Form.Select
                onChange={handleListChange}
                options={vegetablesList}
                placeholder="Ajouter un fruit ou un légume"
                value={addingVegetable}
              />
              <Form.Button id="button" type="submit">Ajouter</Form.Button>
            </Form.Group>
          </Form>
        </div>
      )}

      {(plotStatus === 'actif' && !isUserPlot) && (
        <div>
          {/* console.log('la liste du user actuel : ', plotData.isPlantedOns) */}
          <h2>Cette parcelle est occupée par {plotData.user.name}.</h2>
          <p>Fruits & légumes cultivés</p>
          <ul id="vegetableList">
            {plotVegetableList.map(({ vegetable }) => (
              <li>
                {vegetable.name}
              </li>
            ))}
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
  plotId: PropTypes.string,
  bookPlot: PropTypes.func.isRequired,
};

export default PlotDetail;
