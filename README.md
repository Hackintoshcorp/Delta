# Project Delta

A web control panel for a lightweight antivirus and PC optimization tool. Project Delta pairs a Laravel dashboard with a small local companion program, so you can trigger scans, optimizations and key regeneration from the browser and keep a history of what was run on each device.

> Built as a simple demonstration of how a fairly simple antivirus with device optimization capabilities can be put together.

---

## Features

- **Optimization**: ask the local agent to optimize the computer.
- **One-file scan**: drag and drop a file into the browser. Its MD5 hash is computed client-side with CryptoJS, so the file itself never leaves your machine. Only the hash is sent to the local agent for checking.
- **Full PC scan**: start a scan of the whole computer with one click.
- **Regenerate keys**: regenerate the keys the scanner uses to check for viruses.
- **Device management**: list devices linked to an account, remove them, and share an account key to add new ones.
- **History**: every action is logged with its date, and the "last performed" badge on each card updates live.
- **Account settings**: edit name, surname and email.
- **Status notifications**: toast messages report results, or tell the user when the local program isn't running.

## How it works

```
┌────────────────────┐    HTTP (Laravel routes)    ┌──────────────────┐
│  Browser dashboard │ ──────────────────────────▶ │  Laravel backend │
│  (Blade + jQuery)  │   /history, /remove/device  │  users, history, │
│                    │                             │  devices         │
└─────────┬──────────┘                             └──────────────────┘
          │
          │ POST http://localhost:6969/
          ▼
┌────────────────────┐
│  Local agent       │  performs the optimization / scan / key
│  (runs on the PC)  │  regeneration on the user's machine
└────────────────────┘
```

The dashboard talks to two things:

1. **The Laravel backend** stores users, devices and the execution history.
2. **A local agent** listening on `localhost:6969` does the real work on the user's computer. The browser sends it short commands such as `[optimize]`, `[scanFull]`, `[regenKeys]` and `[one_file]<md5>`. If it isn't running, the UI says so.

## Tech stack

| Layer      | Technology                              |
| ---------- | --------------------------------------- |
| Backend    | PHP, Laravel (Blade templates, auth)    |
| Frontend   | Tailwind CSS, jQuery, CryptoJS          |
| Local tool | Companion program on `localhost:6969`   |

## Backend routes used by this view

| Route             | Method | Purpose                               |
| ----------------- | ------ | ------------------------------------- |
| `logout`          | POST   | Sign the user out                     |
| `account`         | POST   | Save name, surname and email          |
| `/history`        | POST   | Record that an action was performed   |
| `/remove/device`  | POST   | Remove a device from the account      |

The view also expects `$devicesList`, `$lastDates` and `$history` to be passed in by the controller.

## Getting started

```bash
git clone https://github.com/<your-username>/<your-repo>.git
cd <your-repo>

composer install
cp .env.example .env
php artisan key:generate

# configure your database in .env, then:
php artisan migrate

npm install && npm run build   # if you build assets with Vite/Mix
php artisan serve
```

Then start the local agent so it listens on `http://localhost:6969/`, and open the dashboard in your browser.

## Usage

1. Register or log in.
2. Start the local agent on the PC you want to manage.
3. Choose an action on the dashboard: **Optimization**, **One file scan**, **Scan entire PC** or **Regenerate keys**.
4. Watch the toast notification for the result, and check **History** for past runs.
5. Open **Devices** to see linked machines, or to copy the account key for adding a new one.

## Project status and roadmap

This is an early-stage project. Planned or in-progress items:

- [ ] Plan / subscription management (the "Plan" card is a placeholder)
- [ ] "Scan all devices" and "Optimize all devices" actions
- [ ] Per-device actions from the device selector
- [ ] Secure pairing of devices instead of a derived account key
- [ ] Authenticated communication with the local agent

## Contributing

Issues and pull requests are welcome. Please open an issue first to discuss larger changes.

## Author

**Laurynas Bielevičius**

## License

Add a license file (for example MIT) and reference it here.
