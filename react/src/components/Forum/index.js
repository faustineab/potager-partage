import React from 'react';
import PropTypes from 'prop-types';
import { Card, Image, Button, Icon } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import AskQuestion from 'src/containers/AskQuestion';
import './index.scss';

const Forum = ({ questionList }) => (
  <div id="forum">
    <h2>Le coin des jardiniers</h2>
    <AskQuestion />
    {/* {console.log(questionList)} */}

    {questionList.map(({ id, text, title, createdAt, index }) => (
      <Card fluid className="forumCard" key={index} id={id}>
        <Card.Header className="cardHeader"><Link to="/forum/post">{title}</Link></Card.Header>
        <Card.Content>
          <span className="postAuthor">Wassim Alkayar</span>
          <span className="postDate">- publié le {createdAt}</span>
        </Card.Content>
        <Card.Meta>
          <span className="tag">Fruits & Légumes</span>
        </Card.Meta>
        <div className="cardButtons">
          <Button className="cardButton">Voir Plus...</Button>
          <Icon name="ban" size="large" className="cardDelete" />
        </div>
      </Card>
    ))}

  </div>
);

Forum.propTypes = {
  questionList: PropTypes.arrayOf(PropTypes.objectOf).isRequired,
};

export default Forum;
