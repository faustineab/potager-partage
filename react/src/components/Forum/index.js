import React from 'react';
import PropTypes from 'prop-types';
import { Card, Image, Button, Icon } from 'semantic-ui-react';

import AskQuestion from 'src/containers/AskQuestion';
import './index.scss';

const Forum = () => (
  <div id="forum">
    <h2>Le coin des jardiniers</h2>
    <AskQuestion />
    <Card fluid className="forumCard">
      <Card.Header className="cardHeader">A quelle fréquence arroser mes tomates ?</Card.Header>
      <Card.Content>
        <Image src="src/img/004-farmer.svg" avatar />
        <span className="postAuthor">Wassim Alkayar</span>
        <span className="postDate">- publié le 07/05/2019</span>
      </Card.Content>
      <Card.Meta>
        <span className="tag">Fruits & Légumes</span>
      </Card.Meta>
      <div className="cardButtons">
        <Button className="cardButton">Voir Plus...</Button>
        <Icon name="ban" size="large" className="cardDelete" />
      </div>
    </Card>

    <Card fluid className="forumCard">
      <Card.Header className="cardHeader">Une idée pour se débarasser des limaces ?</Card.Header>
      <Card.Content>
        <Image src="src/img/001-farmer.svg" avatar />
        <span className="postAuthor">Vincent Costiou</span>
        <span className="postDate">- publié le 22/04/2019</span>
      </Card.Content>
      <Card.Meta>
        <span className="tag">Trucs & Astuces</span>
      </Card.Meta>
      <div className="cardButtons">
        <Button className="cardButton">Voir Plus...</Button>
        <Icon name="ban" size="large" className="cardDelete" />
      </div>
    </Card>
    <Card fluid className="forumCard">
      <Card.Header className="cardHeader">Que planter avec des petits pois ?</Card.Header>
      <Card.Content>
        <Image src="src/img/003-farmer.svg" avatar />
        <span className="postAuthor">Julie Muru</span>
        <span className="postDate">- publié le 14/03/2019</span>
      </Card.Content>
      <Card.Meta>
        <span className="tag">Fruits & Légumes</span>
      </Card.Meta>
      <div className="cardButtons">
        <Button className="cardButton">Voir Plus...</Button>
        <Icon name="ban" size="large" className="cardDelete" />
      </div>
    </Card>
    <Card fluid className="forumCard">
      <Card.Header className="cardHeader">Achat d'un collecteur de pluie pour le jardin</Card.Header>
      <Card.Content>
        <Image src="src/img/003-farmer.svg" avatar />
        <span className="postAuthor">Faustine Amsellem-Barelli</span>
        <span className="postDate">- publié le 17/02/2019</span>
      </Card.Content>
      <Card.Meta>
        <span className="tag">Autour du jardin</span>
      </Card.Meta>
      <div className="cardButtons">
        <Button className="cardButton">Voir Plus...</Button>
        <Icon name="ban" size="large" className="cardDelete" />
      </div>
    </Card>
    <Card fluid className="forumCard">
      <Card.Header className="cardHeader">Comment se prémunir du gel ?</Card.Header>
      <Card.Content>
        <Image src="src/img/002-farmer.svg" avatar />
        <span className="postAuthor">Sophia Gautier</span>
        <span className="postDate">- publié le 03/01/2019</span>
      </Card.Content>
      <Card.Meta>
        <span className="tag">Fruits & Légumes</span>
      </Card.Meta>
      <div className="cardButtons">
        <Button className="cardButton">Voir Plus...</Button>
        <Icon name="ban" size="large" className="cardDelete" />
      </div>
    </Card>
  </div>
);

export default Forum;
