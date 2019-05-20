import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Card, Icon, Button, Comment, Form, Header } from 'semantic-ui-react';

import './index.scss';

class PostDetail extends Component {
  componentDidMount() {
    const { fetchQuestionDetail } = this.props;
    fetchQuestionDetail();
  }

  render() {
    const { match } = this.props;
    // const { id } = match.params;
    return (
      <main id="postDetail">
        <Card fluid className="forumCard">
          <Card.Header className="cardHeader">A quelle fréquence arroser mes tomates ?</Card.Header>
          <Card.Content>
            <span className="postAuthor">Wassim Alkayar</span>
            <span className="postDate">- publié le 07/05/2019</span>
          </Card.Content>
          <Card.Meta>
            <span className="tag">Fruits & Légumes</span>
          </Card.Meta>
          <div className="cardButtons">
            <Icon name="ban" size="large" className="cardDelete" />
          </div>
        </Card>
        <Comment.Group id="postComments">
          <Header as="h3" dividing>
            Réponses
          </Header>

          <Comment>
            <Comment.Content>
              <Comment.Content id="commentHeader">
                <Comment.Author>Matt</Comment.Author>
                <Icon name="ban" className="cardDelete commentDelete" />
              </Comment.Content>
              <Comment.Metadata>
                <div>Today at 5:42PM</div>
              </Comment.Metadata>
              <Comment.Text>How artistic!</Comment.Text>
            </Comment.Content>
          </Comment>

          <Comment>
            <Comment.Content>
              <Comment.Content id="commentHeader">
                <Comment.Author>Matt</Comment.Author>
                <Icon name="ban" className="cardDelete commentDelete" />
              </Comment.Content>
              <Comment.Metadata>
                <div>Today at 5:42PM</div>
              </Comment.Metadata>
              <Comment.Text>How artistic!</Comment.Text>
            </Comment.Content>
          </Comment>
        </Comment.Group>
        <Form id="submitComment" reply>
          <Form.TextArea />
          <Button content="Répondre" floated="right" />
        </Form>
      </main>
    );
  }
}

PostDetail.propTypes = {
  fetchQuestionDetail: PropTypes.func.isRequired,
};

export default PostDetail;
