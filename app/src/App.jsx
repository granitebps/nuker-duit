import React from 'react';
import { Routes, Route } from 'react-router-dom';

import Login from './pages/Login';

function App() {
  return (
    <Routes>
      <Route index element={<Login />} />
      {/* <Route path="/" element={<Layout />}>
                <Route path="reminder" element={<Reminder />} />
                <Route path="reminder-create" element={<ReminderCreate />} />
                <Route
                    path="reminder-update/:id"
                    element={<ReminderUpdate />}
                />

                <Route path="*" element={<NoMatch />} />
            </Route> */}
    </Routes>
  );
}

export default App;
