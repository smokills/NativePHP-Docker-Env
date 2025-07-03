## NativePHP Docker Bootstrap

To start development, first we have to share `X11` with the container (not working with Wayland)
```bash
xhost +local:root
```

After that we can start our development with:
```bash
docker compose up --build
```
