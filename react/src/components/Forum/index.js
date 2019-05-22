import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Card, Button, Icon } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import AskQuestion from 'src/containers/AskQuestion';
import './index.scss';

class Forum extends Component {
  componentDidMount() {
    const { openForum } = this.props;
    openForum();
  }

  handleDeleteCard = (evt) => {
    const { deleteCard } = this.props;
    const cardId = evt.currentTarget.id;
    console.log(cardId);
    deleteCard(cardId);
  };

  handleOpenPost = (evt) => {
    const { saveQuestionId } = this.props;
    const questionId = evt.currentTarget.id;
    console.log(questionId);
    saveQuestionId(questionId);
  }

  render() {
    const { questionList } = this.props;
    console.log('props', this.props);
    return (
      <div id="forum">
        <h2>Le coin des jardiniers</h2>
        <AskQuestion />
        {questionList.map(({ id, title, creationDate, index, user, tags }) => (
          <Card fluid className="forumCard" key={index} id={id}>
            <Card.Header className="cardHeader" content={title} />
            <Card.Content>
              <span className="postAuthor">{user.name}</span>
              <span className="postDate">- publié le {creationDate}</span>
            </Card.Content>
            <Card.Meta>
              {tags.map(({ name }) => <span className="tag">{name}</span>)}
            </Card.Meta>
            <div className="cardButtons">
              <Button className="cardButton" id={id} as={Link} to={`/forum/post/${id}`} onClick={this.handleOpenPost}>Voir Plus...</Button>
              <Icon name="ban" size="large" className="cardDelete" id={id} onClick={this.handleDeleteCard} />
            </div>
          </Card>
        ))}
      </div>
    );
  }
}


Forum.propTypes = {
  questionList: PropTypes.arrayOf(PropTypes.objectOf).isRequired,
  deleteCard: PropTypes.func.isRequired,
  openForum: PropTypes.func.isRequired,
  saveQuestionId: PropTypes.func.isRequired,
};

export default Forum;
