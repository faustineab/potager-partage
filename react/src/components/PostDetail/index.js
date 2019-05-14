import React from 'react';
import PropTypes from 'prop-types';
import { Card, Image, Button, Icon, Comment, Form, Header } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import './index.scss';

const PostDetail = () => (
  <main id="postDetail">
    <p>PostDetail</p>
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
        {/* <Icon name="ban" size="large" className="cardDelete" /> */}
      </div>
    </Card>
    <Comment.Group id="postComments">
    <Header as='h3' dividing>
      Réponses
    </Header>

    <Comment>
      <Comment.Avatar src='/images/avatar/small/matt.jpg' />
      <Comment.Content>
        <Comment.Author>Matt</Comment.Author>
        <Comment.Metadata>
          <div>Today at 5:42PM</div>
        </Comment.Metadata>
        <Comment.Text>How artistic!</Comment.Text>
        <Comment.Actions>
          <Comment.Action>Reply</Comment.Action>
        </Comment.Actions>
      </Comment.Content>
    </Comment>

    <Comment>
      <Comment.Avatar src='/images/avatar/small/elliot.jpg' />
      <Comment.Content>
        <Comment.Author as='a'>Elliot Fu</Comment.Author>
        <Comment.Metadata>
          <div>Yesterday at 12:30AM</div>
        </Comment.Metadata>
        <Comment.Text>
          <p>This has been very useful for my research. Thanks as well!</p>
        </Comment.Text>
        <Comment.Actions>
          <Comment.Action>Reply</Comment.Action>
        </Comment.Actions>
      </Comment.Content>
    </Comment>

    <Form reply>
      <Form.TextArea />
      <Button content='Add Reply' labelPosition='left' icon='edit' primary />
    </Form>
  </Comment.Group>
  </main>
);

export default PostDetail;
