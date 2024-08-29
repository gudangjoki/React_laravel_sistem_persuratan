import React from 'react';
import { BrowserRouter } from 'react-router-dom';
import Root from './Root';

const Main = () => {
  return (
    <BrowserRouter>
      <Root />
    </BrowserRouter>
  );
};

export default Main;
