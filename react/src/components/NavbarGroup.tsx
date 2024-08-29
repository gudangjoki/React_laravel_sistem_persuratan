import profile from "/public/profile-admin.jpg";

const NavbarGroup = () => {
  return (
    <div className="w-full h-[4rem] flex items-center font-roboto">
      <div className="flex w-full justify-between items-center h-1/2">
        <div className="font-bold text-xl py-3">Dashboard</div>
        <>
          <div className="flex items-center w-1/4 p-1 gap-2 rounded-lg bg-white">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              x="0px"
              y="0px"
              width="120"
              height="120"
              viewBox="0 0 50 50"
              className="w-full h-full ml-1"
            >
              <path d="M 21 3 C 11.601563 3 4 10.601563 4 20 C 4 29.398438 11.601563 37 21 37 C 24.355469 37 27.460938 36.015625 30.09375 34.34375 L 42.375 46.625 L 46.625 42.375 L 34.5 30.28125 C 36.679688 27.421875 38 23.878906 38 20 C 38 10.601563 30.398438 3 21 3 Z M 21 7 C 28.199219 7 34 12.800781 34 20 C 34 27.199219 28.199219 33 21 33 C 13.800781 33 8 27.199219 8 20 C 8 12.800781 13.800781 7 21 7 Z"></path>
            </svg>
            <input
              type="text"
              placeholder="Search here..."
              className="p-1 outline-none"
            />
          </div>

          <div className="w-10 h-10">
            <img
              src={profile}
              className="w-full h-full rounded-full"
              alt="admin"
            />
          </div>
        </>
      </div>
    </div>
  );
};

export default NavbarGroup;
