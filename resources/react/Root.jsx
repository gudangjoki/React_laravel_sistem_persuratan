import React from 'react';
import { Routes, Route } from 'react-router-dom';
import Home from './pages/Home';

const Root = () => {
  return (
    <Routes>
      <Route path="/home" element={<Home />} />
    </Routes>
  );
};

export default Root;
