import React from 'react';
import { Routes, Route } from 'react-router-dom';

import Login from './pages/Login';
import Layout from './pages/layouts/Layout';
import Home from './pages/Home';
import Summary from './pages/Summary';

function App() {
  return (
    <Routes>
      <Route index element={<Login />} />
      <Route path='/' element={<Layout />}>
        <Route path='home' element={<Home />} />
        <Route path='summary' element={<Summary />} />
        {/* <Route path="reminder-create" element={<ReminderCreate />} />
                <Route
                    path="reminder-update/:id"
                    element={<ReminderUpdate />}
                />

                <Route path="*" element={<NoMatch />} /> */}
      </Route>
    </Routes>
  );
}

export default App;
