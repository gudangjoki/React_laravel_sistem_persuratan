import chad from "/public/chad.png";
import useDateDay from "../hooks/useDateDay";

const MyProfile = () => {
  // const { leftComponentClicked, rightComponentClicked } = useDateDay();
  const { leftComponentClicked } = useDateDay();

  return (
    <div className="py-7 px-11 font-poppins w-full">
      {/* header */}
      <div className="flex justify-between items-center">
        <div className="text-[#666666] text-lg font-semibold">My Profile</div>
        <div className="flex items-center gap-x-5">
          <button className="rounded-md bg-[#FFD43D] px-8 py-3 text-[#666666]">
            Edit
          </button>
          <button className="rounded-md bg-[#1ED760] px-8 py-3 text-white">
            Save
          </button>
        </div>
      </div>
      {/* header */}

      {/* user information */}
      <div className="mt-5 border-[#D9D9D9] border-[1.5px] shadow-md text-[#666666] h-30">
        <div className="py-4 px-9">
          <div className="flex gap-x-7">
            <img src={chad} alt="chad" className="w-[10rem]" />
            <div className="text-sm flex flex-col justify-between">
              <div className="">
                Nama:{" "}
                <span className={`absolute ${leftComponentClicked ? `right-[24.2rem]` : `left-[23rem]`}`}>Kelvin Kusuma</span>
              </div>
              <div className="">
                No. Telp:{" "}
                <span className={`absolute ${leftComponentClicked ? `right-[24.2rem]` : `left-[23rem]`}`}>087899507840</span>
              </div>
              <div className="">
                Email:{" "}
                <span className={`absolute ${leftComponentClicked ? `left-[44rem]` : `left-[23rem]`}`}>
                  kelvinkusuma@gmail.com
                </span>
              </div>
              <div className="">
                Alamat:{" "}
                <span className={`absolute ${leftComponentClicked ? `left-[44rem]` : `left-[23rem]`}`}>
                  Jln. Nakula Sadewa, no.1, ds.bukit, kec.jimbaran, kab.badung,
                  prov.bali
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* user information */}

      {/* user input */}
      <div className="mt-9 w-full text-sm text-[#666666] flex flex-col gap-y-6">
        <div className="flex items-center w-full gap-[4rem]">
          <div className="w-1/2">
            <p>Event Name</p>
            <input type="text" className="shadow-md w-full p-2" />
          </div>
          <div className="w-1/2">
            <p>Event Name</p>
            <input type="text" className="shadow-md w-full p-2" />
          </div>
        </div>

        <div className="w-full">
          <p>Alamat</p>
          <input type="text" className="shadow-md w-full p-2" />
        </div>
        <div className="w-1/2">
          <p>Email</p>
          <input type="text" className="shadow-md w-3/4 p-2" />
        </div>
      </div>
      {/* user input */}
    </div>
  );
};

export default MyProfile;
