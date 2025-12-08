// resources/js/Layouts/GuestLayout.jsx
import React from 'react';

export default function GuestLayout({ children }) {
  return (
    <div className="container">
      <div className="main-content d-flex flex-column p-0">
        <div className="m-auto m-1230">
          <div className="row align-items-center">
            <div className="col-lg-6 d-none d-lg-block">
              <img src="/assets/images/login1.jpg" className="rounded-3" alt="login" />
            </div>
            <div className="col-lg-6">
              <div className="mw-480 ms-lg-auto">
                {children}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
